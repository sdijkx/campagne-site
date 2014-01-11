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
use GLZeist\Bundle\ProgrammaBundle\Entity\Hoofdstuk;
use GLZeist\Bundle\ProgrammaBundle\Form\HoofdstukType;
use GLZeist\Bundle\ProgrammaBundle\Annotation\Granted;

/**
 * Hoofdstuk controller.
 * @Granted(role="ROLE_MODERATOR")
 * @Route("/hoofdstuk")
 */
class HoofdstukController extends Controller
{
    /**
     * Lists all Hoofdstuk entities.
     *
     * @Route("/", name="admin_hoofdstuk")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GLZeistProgrammaBundle:Hoofdstuk')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    
    
    /**
     * Displays a form to create a new Hoofdstuk entity.
     *
     * @Route("/new", name="hoofdstuk_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Hoofdstuk();
        $form   = $this->createForm(new HoofdstukType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Hoofdstuk entity.
     *
     * @Route("/create", name="hoofdstuk_create")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Hoofdstuk:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Hoofdstuk();
        $form = $this->createForm(new HoofdstukType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice','Het hoofdstuk is opgeslagen');
            return $this->redirect($this->generateUrl('hoofdstuk_edit', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Hoofdstuk entity.
     *
     * @Route("/{id}/edit", name="hoofdstuk_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Hoofdstuk')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Hoofdstuk entity.');
        }

        $editForm = $this->createForm(new HoofdstukType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Hoofdstuk entity.
     *
     * @Route("/{id}/update", name="hoofdstuk_update")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Hoofdstuk:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Hoofdstuk')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Hoofdstuk entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new HoofdstukType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('hoofdstuk_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Hoofdstuk entity.
     *
     * @Route("/{id}/delete", name="hoofdstuk_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GLZeistProgrammaBundle:Hoofdstuk')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Hoofdstuk entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_hoofdstuk'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
     * Finds and displays a Hoofdstuk entity.
     *
     * @Method("GET")
     * @Route("/{id}/preview", name="admin_hoofdstuk_preview")
     * @Template("GLZeistProgrammaBundle:App:Hoofdstuk/hoofdstuk.html.twig")
     */
    
    public function previewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Hoofdstuk')->findOneById($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Hoofdstuk entity.');
        }
        return array(
            'hoofdstuk'      => $entity,
            'items' => $em->getRepository('GLZeistProgrammaBundle:Item')->findByHoofdstuk($entity)
        );        
    }
    

    /**
     * @Route("/{id}/afbeelding/{afbeeldingId}",name="hoofdstuk_admin_afbeelding", defaults={"slug"="slug"})
     * @Route("/{id}/{slug}/afbeelding/{afbeeldingId}",name="hoofdstuk_admin_afbeelding_slug")
     * @Method("GET")
     * @Template()
     */
    public function afbeeldingAction($id,$slug, $afbeeldingId) {

        $hoofdstuk = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Hoofdstuk')->findOneById($id);
        if (!$hoofdstuk) 
        {
            throw $this->createNotFoundException();        
        }
        
        $afbeeldingenService=$this->get('gl_zeist_programma.afbeeldingen_service');
        try {
            $afbeeldingenService->afbeeldingNaarStdout($hoofdstuk,$afbeeldingId);
        }
        catch(\Exception $e) {
            throw $this->createNotFoundException($e);        
        }
        exit;
    }    
}
