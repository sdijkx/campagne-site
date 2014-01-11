<?php
/*
    This file is part of GroenLinks Zeist Campagnesite.

    GroenLinks Zeist Campagnesite is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    GroenLinks Zeist Campagnesite is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GroenLinks Zeist Campagnesite.  If not, see <http://www.gnu.org/licenses/>.
    
*/

namespace GLZeist\Bundle\ProgrammaBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use GLZeist\Bundle\ProgrammaBundle\Entity\Item;
use GLZeist\Bundle\ProgrammaBundle\Form\ItemType;
use GLZeist\Bundle\ProgrammaBundle\Annotation\Granted;

/**
 * Item controller.
 * @Granted(role="ROLE_USER")
 * @Route("/item")
 */
class ItemController extends Controller
{
    /**
     * Lists all Item entities.
     *
     * @Route("/", name="admin_item")
     * @Template()
     */
    public function indexAction()
    {
        $securityContext=$this->get('security.context');
        $em = $this->getDoctrine()->getManager();
        
        $orderBy=$this->getRequest()->get('orderby','i.gewijzigdOp');
        $sortOrder=$this->getRequest()->get('sortorder','DESC');
        $search=$this->getRequest()->get('search',null);
        $state=$this->getRequest()->get('state','all');
        $page=$this->getRequest()->get('page',1);
        
        $limit=$this->getRequest()->get('limit',10);
        
        $offset=($page-1)*$limit;
        

        
        $fields=array('i.titel' => 'Titel','i.kernboodschap' => 'Kernboodschap','i.tweet' => 'Tweet','i.gewijzigdOp' => 'Gewijzigd op');

        $qb=$em->createQueryBuilder();
        $qb
            ->select('i')
            ->from('GLZeistProgrammaBundle:Item','i')
            ->leftJoin('i.publishedItem','p')
            ->orderBy($orderBy,$sortOrder);

        if(!empty($search))
        {
            $qb->andWhere('i.titel LIKE :search OR i.kernboodschap LIKE :search OR i.hoofdtekst LIKE :search OR i.tweet LIKE :search');
            $qb->setParameter('search',$search);
        }

        if(!empty($state))
        {
            if($state=='published')
            {
                $qb->andWhere('i.gewijzigdOp<=p.gepubliceerdOp');
            }
            elseif($state=='unpublished')
            {
                $qb->andWhere('i.publishedItem IS NULL ');
            }
            elseif($state=='changed')
            {
                $qb->andWhere('i.gewijzigdOp>p.gepubliceerdOp');
            }
            
        }
        
        if(!$securityContext->isGranted('ROLE_MODERATOR'))
        {
            $qb->andWhere('i.gemaaktDoor=:user');
            $qb->setParameter('user',$this->getUser());
        }
        $qb->setFirstResult( $offset );
        $qb->setMaxResults( $limit );
        
        $query=$qb->getQuery();
        
        $paginator=new \Doctrine\ORM\Tools\Pagination\Paginator($query);
        $count=$paginator->count();

        $pageCount=floor(($count-1)/$limit+1);
        $offset=($page-1)*$limit;
        
        $pages=array();
        for($i=1;$i<=$pageCount;$i++)
        {
            $pages[$i]=$i;
        }
        
                
        //$entities = $em->getRepository('GLZeistProgrammaBundle:Item')->findAll(array($orderBy => $sortOrder));

        return array(
            'entities' => $paginator,
            'fields' => $fields,
            'search' => $search,
            'orderBy'=>$orderBy,
            'sortOrder' => $sortOrder,
            'state' => $state,
            'pageCount' => $pageCount,
            'page' => $page,
            'pages' => $pages
            
        );
    }

   

    /**
     * Finds and displays a Item entity.
     *
     * @Route("/{id}/preview", name="admin_preview_item")
     * @Template("GLZeistProgrammaBundle:App:PublishedItem/detail.html.twig")
     */
    public function previewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Item')->findByIdForUser($id,$this->getUser());

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Item entity.');
        }
        return array(
            'item'      => $entity,
        );
    }    

    /**
     * Displays a form to create a new Item entity.
     *
     * @Route("/new", name="item_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Item();
        $form   = $this->createForm(new ItemType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Item entity.
     *
     * @Route("/create", name="item_create")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Item:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Item();
        $form = $this->createForm(new ItemType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            
            try {
                $em = $this->getDoctrine()->getManager();
                $entity->setGemaaktDoor($this->getUser());
                $em->persist($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice','Het item is opgeslagen');
                return $this->redirect($this->generateUrl('item_edit', array('id' => $entity->getId())));
            }
            catch(\Exception $e)
            {
                $this->get('session')->getFlashBag->add('error','Het item kan niet worden opgeslagen');
            }
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Item entity.
     *
     * @Route("/{id}/edit", name="item_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Item')->findByIdForUser($id,$this->getUser());

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Item entity.');
        }

        $editForm = $this->createForm(new ItemType(), $entity);
        $deleteForm = $this->createDeleteForm($id);
        $publishForm = $this->createPublishForm($id);
        $unpublishForm = $this->createUnpublishForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'publish_form' => $publishForm->createView(),
            'unpublish_form' => $unpublishForm->createView()
        );
    }

    /**
     * Edits an existing Item entity.
     *
     * @Route("/{id}/update", name="item_update")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Admin/Item:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Item')->findByIdForUser($id,$this->getUser());
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Item entity.');
        }
        
        $links=array();
        foreach($entity->getLinks() as $link)
        {
            $links[]=$link;
        }
        
        $afbeeldingen=array();
        foreach($entity->getAfbeeldingen() as $afbeelding)
        {
            $afbeeldingen[]=$afbeelding;
        }
        

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ItemType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            //remove links
            foreach($entity->getLinks() as $link)
            {
                foreach ($links as $key => $toDel) {
                    if ($toDel->getId() === $link->getId()) {
                        unset($links[$key]);
                    }
                }                
            }
            foreach($links as $link)
            {
                $link->setItem(null);
                $em->remove($link);
            }

            foreach($entity->getAfbeeldingen() as $afbeelding)
            {
                foreach ($afbeelding as $key => $toDel) {
                    if ($afbeelding->getId() === $afbeelding->getId()) {
                        unset($afbeeldingen[$key]);
                    }
                }                
            }
            foreach($afbeeldingen as $afbeelding)
            {
                $afbeelding->setItem(null);
                $em->remove($afbeelding);
            }
            
            $em->persist($entity);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('notice','Het item is opgeslagen');

            return $this->redirect($this->generateUrl('item_edit', array('id' => $id)));
        }
        else
        {
            $this->get('session')->getFlashBag()->add('error','Het formulier bevat fouten');
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'publish_form' => $this->createPublishForm($id)->createView(),
            'unpublish_form' => $this->createUnpublishForm($id)->createView()
        );
    }
    
    /**
     * @Route("/{id}/publish",name="admin_publiceer_item")
     * @Granted(role="ROLE_MODERATOR")
     * @Method("POST")
     */    
    public function publishAction(Request $request, $id)
    {
        
        $form = $this->createPublishForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GLZeistProgrammaBundle:Item')->findByIdForUser($id,$this->getUser());

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Item entity.');
            }
            
            $publisher=$this->get('gl_zeist_programma.publish_service');
            try
            {
                $publisher->publish($entity);
                $this->get('session')->getFlashBag()->add('notice','Het item is gepubliceerd');
            }
            catch(\Exception $e)
            {
                $this->get('session')->getFlashBag()->add('error',$e->getMessage());
            }
        }
        else
        {
            foreach($form->getErrors() as $error)
            {
                $this->get('session')->getFlashBag()->add('error',$error->getMessage());
            }
        }
        return $this->redirect($this->generateUrl('item_edit',array('id'=>$id)));        
    }

    /**
     * @Route("/{id}/unpublish",name="admin_unpublish_item")
     * @Granted(role="ROLE_MODERATOR")
     * @Method("POST")
     */    
    public function unpublishAction(Request $request, $id)
    {
        
        $form = $this->createPublishForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GLZeistProgrammaBundle:Item')->findByIdForUser($id,$this->getUser());

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Item entity.');
            }
            
            $publisher=$this->get('gl_zeist_programma.publish_service');
            try
            {
                $publisher->unpublish($entity);
                $this->get('session')->getFlashBag()->add('notice','Het item is teruggetrokken');
            }
            catch(\Exception $e)
            {
                $this->get('session')->getFlashBag()->add('error',$e->getMessage());
            }
        }
        else
        {
            foreach($form->getErrors() as $error)
            {
                $this->get('session')->getFlashBag()->add('error',$error->getMessage());
            }
        }
        return $this->redirect($this->generateUrl('item_edit',array('id'=>$id)));        
    }
    
    /**
     * @Route("/{id}/request-publication",name="admin_publicatie_aanvraag")
     * @Granted(role="ROLE_USER")
     * @Method("POST")
     */    
    public function requestPublicationAction(Request $request, $id)
    {

        $form = $this->createPublishForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GLZeistProgrammaBundle:Item')->findByIdForUser($id,$this->getUser());
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Item entity.');
            }
            
            $publisher=$this->get('gl_zeist_programma.publish_service');
            try
            {
                $publisher->requestPublication($entity);
                $this->get('session')->getFlashBag()->add('notice','Aanvraag ingediend');
                return $this->redirect($this->generateUrl('admin_item',array('id'=>$id)));        
            }
            catch(\Exception $e)
            {
                $this->get('session')->getFlashBag()->add('error',$e->getMessage());
            }
        }
        else
        {
            foreach($form->getErrors() as $error)
            {
                $this->get('session')->getFlashBag()->add('error',$error->getMessage());
            }
        }

        return $this->redirect($this->generateUrl('item_edit',array('id'=>$id)));        
    }
    

    /**
     * Deletes a Item entity.
     *
     * @Route("/{id}/delete", name="item_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GLZeistProgrammaBundle:Item')->findByIdForUser($id,$this->getUser());

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Item entity.');
            }
            $em->beginTransaction();
            try
            {
                if($entity->getPublishedItem())
                {
                    $publishedItem=$entity->getPublishedItem();
                    $publishedItem->setItem(null);
                    $entity->setPublishedItem(null);
                    $em->remove($publishedItem);
                    $em->flush();
                }
                $em->remove($entity);
                $em->flush();
                $em->commit();
                $this->get('session')->getFlashBag()->add('notice','Het item is verwijderd');
                return $this->redirect($this->generateUrl('admin_item'));
            }
            catch(\Exception $e)
            {
                $em->rollback();
                $this->get('session')->getFlashBag()->add('error','Het item kan niet verwijderd worden'.$e->getMessage());
            }
            
        }

        return $this->redirect($this->generateUrl('item_edit',array('id'=>$id)));
    }

    /**
     * @Route("/{id}/afbeelding/{afbeeldingId}",name="item_admin_afbeelding", defaults={"slug"="slug"})
     * @Route("/{id}/{slug}/afbeelding/{afbeeldingId}",name="item_admin_afbeelding_slug")
     * @Method("GET")
     * @Template()
     */
    public function afbeeldingAction($id,$slug, $afbeeldingId) {

        $item = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Item')->findOneById($id);
        if (!$item) 
        {
            throw $this->createNotFoundException();        
        }
        
        $afbeeldingenService=$this->get('gl_zeist_programma.afbeeldingen_service');
        try {
            $afbeeldingenService->afbeeldingNaarStdout($item,$afbeeldingId);
        }
        catch(\Exception $e) {
            throw $this->createNotFoundException($e);        
        }
        exit;
    }
    

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    private function createPublishForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    private function createUnpublishForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    
}
