<?php

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
        $page=$this->getRequest()->get('page',1);
        
        $limit=10;
        $offset=($page-1)*$limit;
        

        
        $fields=array('i.titel' => 'Titel','i.kernboodschap' => 'Kernboodschap','i.tweet' => 'Tweet','i.gewijzigdOp' => 'Datum');

        $qb=$em->createQueryBuilder();
        $qb
            ->select('i')
            ->from('GLZeistProgrammaBundle:Item','i')
            ->orderBy($orderBy,$sortOrder);
        if(!empty($search))
        {
            $qb->andWhere('i.zoektekst LIKE :search OR i.zoektrefwoorden LIKE :search');
            $qb->setParameter('search',$search);
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

        $pageCount=($count-1)/$limit+1;
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
            'pageCount' => $pageCount,
            'page' => $page,
            'pages' => $pages
            
        );
    }

    /**
     * Finds and displays a Item entity.
     *
     * @Route("/{id}/show", name="item_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Item')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Item entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }
    

    /**
     * Finds and displays a Item entity.
     *
     * @Route("/{id}/preview", name="admin_preview_item")
     * @Template("GLZeistProgrammaBundle:App:Item/detail.html.twig")
     */
    public function previewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Item')->find($id);

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
            $em = $this->getDoctrine()->getManager();
            $entity->setGemaaktDoor($this->getUser());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('item_edit', array('id' => $entity->getId())));
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

        $entity = $em->getRepository('GLZeistProgrammaBundle:Item')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Item entity.');
        }

        $editForm = $this->createForm(new ItemType(), $entity);
        $deleteForm = $this->createDeleteForm($id);
        $publishForm = $this->createPublishForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'publish_form' => $publishForm->createView()
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

        $entity = $em->getRepository('GLZeistProgrammaBundle:Item')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Item entity.');
        }
        
        $links=array();
        foreach($entity->getLinks() as $link)
        {
            $links[]=$link;
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ItemType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            if($entity->getFile()!==null)
            {
                $entity->setImagefile(rand());
                $entity->setThumbfile(rand());
            }
            
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
            
           
            
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('item_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'publish_form' => $this->createPublishForm($id)->createView() 
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
            $entity = $em->getRepository('GLZeistProgrammaBundle:Item')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Item entity.');
            }
            
            $publisher=$this->get('gl_zeist_programma.publish_service');
            try
            {
                $publisher->publish($entity);
            }
            catch(\Exception $e)
            {
                $this->get('session')->setFlash('error',$e->getMessage());
            }
        }

        return $this->redirect($this->generateUrl('admin_item',array('id'=>$id)));        
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
            $entity = $em->getRepository('GLZeistProgrammaBundle:Item')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Item entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('item'));
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
    
}
