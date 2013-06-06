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

namespace GLZeist\Bundle\ProgrammaBundle\Controller\Admin\Editor;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use GLZeist\Bundle\ProgrammaBundle\Entity\Hoofdstuk;
use GLZeist\Bundle\ProgrammaBundle\Form\HoofdstukType;

/**
 * Hoofdstuk controller.
 *
 * @Route("/editor/hoofdstuk")
 */
class HoofdstukController extends Controller
{

    /**
     * Displays a form to create a new Hoofdstuk entity.
     *
     * @Route("/new", name="editor_hoofdstuk_new")
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
     * @Route("/create", name="editor_hoofdstuk_create")
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

            return $this->redirect($this->generateUrl('editor_hoofdstuk_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Hoofdstuk entity.
     *
     * @Route("/{id}/edit", name="editor_hoofdstuk_edit")
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
     * @Route("/{id}/update", name="editor_hoofdstuk_update")
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
     * @Route("/{id}/delete", name="editor_hoofdstuk_delete")
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

        return new Response('OK');
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
