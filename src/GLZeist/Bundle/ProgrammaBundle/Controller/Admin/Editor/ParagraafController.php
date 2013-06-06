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
use GLZeist\Bundle\ProgrammaBundle\Entity\Paragraaf;
use GLZeist\Bundle\ProgrammaBundle\Form\ParagraafType;

/**
 * Paragraaf controller.
 *
 * @Route("/editor/paragraaf")
 */
class ParagraafController extends Controller
{

    /**
     * Finds and displays a Paragraaf entity.
     *
     * @Route("/{id}/show", name="paragraaf_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Paragraaf')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Paragraaf entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Paragraaf entity.
     *
     * @Route("/new", name="editor_paragraaf_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Paragraaf();
        $form   = $this->createForm(new ParagraafType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Paragraaf entity.
     *
     * @Route("/create", name="editor_paragraaf_create")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Paragraaf:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Paragraaf();
        $form = $this->createForm(new ParagraafType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('paragraaf_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Paragraaf entity.
     *
     * @Route("/{id}/edit", name="editor_paragraaf_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Paragraaf')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Paragraaf entity.');
        }

        $editForm = $this->createForm(new ParagraafType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Paragraaf entity.
     *
     * @Route("/{id}/update", name="editor_paragraaf_update")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Paragraaf:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Paragraaf')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Paragraaf entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ParagraafType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('paragraaf_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Paragraaf entity.
     *
     * @Route("/{id}/delete", name="editor_paragraaf_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GLZeistProgrammaBundle:Paragraaf')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Paragraaf entity.');
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
