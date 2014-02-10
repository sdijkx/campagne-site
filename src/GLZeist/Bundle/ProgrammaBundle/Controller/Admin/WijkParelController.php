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
use GLZeist\Bundle\ProgrammaBundle\Entity\Wijk;
use GLZeist\Bundle\ProgrammaBundle\Form\WijkParelType;
use GLZeist\Bundle\ProgrammaBundle\Entity\WijkParel;
use GLZeist\Bundle\ProgrammaBundle\Annotation\Granted;

/**
 * Item controller.
 * @Granted(role="ROLE_MODERATOR")
 * @Route("/wijkparel")
 */
class WijkParelController extends Controller {

    
    /**
     * Displays a form to create a new Hoofdstuk entity.
     *
     * @Route("/new/{wijkId}", name="admin_wijkparel_new")
     * @Template()
     */
    public function newAction($wijkId)
    {
        $wijk = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Wijk')->find($wijkId);
        if(!$wijk) {
            throw $this->createNotFoundException();
        }
        $entity = new WijkParel();
        $entity->setWijk($wijk);
        $form   = $this->createForm(new WijkParelType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            
        );
    }

    /**
     * Creates a new Wijk entity.
     *
     * @Route("/create/{wijkId}", name="admin_wijkparel_create")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Wijk:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $wijkId=$request->get('wijkId');
        $wijk = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Wijk')->find($wijkId);
        if(!$wijk) {
            throw $this->createNotFoundException();
        }
        $entity = new WijkParel();        
        $entity->setWijk($wijk);
        $form = $this->createForm(new WijkParelType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice','De wijkparel is opgeslagen');
            return $this->redirect($this->generateUrl('admin_wijkparel_edit', array('wijkId' => $entity->getWijk()->getId(),'id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Wijk entity.
     *
     * @Route("/{id}/edit", name="admin_wijkparel_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('GLZeistProgrammaBundle:WijkParel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WijkParel entity.');
        }

        $editForm = $this->createForm(new WijkParelType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Wijk entity.
     *
     * @Route("/{id}/update", name="admin_wijkparel_update")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:WijkParel:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:WijkParel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WijkParel entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new WijkParelType(), $entity);
        
        $afbeeldingen=array();
        foreach($entity->getAfbeeldingen() as $afbeelding) {
            $afbeeldingen[$afbeelding->getId()]=$afbeelding;
        }
        
        $links=array();
        foreach($entity->getLinks() as $link) {
            $links[$link->getId()]=$link;
        }        
        
        $editForm->bind($request);

        if ($editForm->isValid()) 
        {
            
 foreach($entity->getAfbeeldingen() as $afbeelding) {
                $afbeelding->setWijkParel($entity);
                if(isset($afbeeldingen[$afbeelding->getId()])) {
                    unset($afbeeldingen[$afbeelding->getId()]);
                }
            }
            foreach($afbeeldingen as $afbeelding) {
                $afbeelding->setWijkParel(null);
                $em->remove($afbeelding);
            }
            
            foreach($entity->getLinks() as $link) {
                $link->setWijkParel($entity);
                if(isset($links[$link->getId()])) {
                    unset($links[$link->getId()]);
                }
            }
              foreach($links as $link) {
                $link->setWijkParel(null);
                $em->remove($link);
            }            
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_wijkparel_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Wijk entity.
     *
     * @Route("/{id}/delete", name="admin_wijkparel_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GLZeistProgrammaBundle:WijkParel')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find WijkParel entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_wijk_edit',array('id' => $entity->getWijk()->getId())));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    
    /**
     * @Route("/{id}/wijkparel/{wijkParelId}/afbeelding/{afbeeldingId}",name="admin_wijkparel_afbeelding", defaults={"slug"="slug"})
     * @Route("/{id}/{slug}/wijkparel/{wijkParelId}/afbeelding/{afbeeldingId}",name="admin_wijkparel_afbeelding_slug")
     * @Method("GET")
     * @Template()
     */
    public function afbeeldingAction($id,$slug, $wijkParelId, $afbeeldingId) {

        $wijkParel = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:WijkParel')->findOneById($id);
        if (!$wijkParel) 
        {
            throw $this->createNotFoundException();        
        }
        
        $afbeeldingenService=$this->get('gl_zeist_programma.afbeeldingen_service');
        try {
            $afbeeldingenService->afbeeldingNaarStdout($wijkParel,$afbeeldingId);
        }
        catch(\Exception $e) {
            throw $this->createNotFoundException($e);        
        }
        exit;
    }            
}
