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
use GLZeist\Bundle\ProgrammaBundle\Entity\Media;
use GLZeist\Bundle\ProgrammaBundle\Form\MediaType;
use GLZeist\Bundle\ProgrammaBundle\Annotation\Granted;

/**
 * Media controller.
 * @Granted(role="ROLE_MODERATOR")
 * @Route("/media")
 */
class MediaController extends Controller
{
    /**
     * Lists all Media entities.
     *
     * @Route("/", name="media")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GLZeistProgrammaBundle:Media')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Media entity.
     *
     * @Route("/{id}/show", name="media_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Media')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Media entity.
     *
     * @Route("/new", name="media_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Media();
        $form   = $this->createForm(new MediaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Media entity.
     *
     * @Route("/create", name="media_create")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Media:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Media();
        $form = $this->createForm(new MediaType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('media_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Media entity.
     *
     * @Route("/{id}/edit", name="media_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Media')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }

        $editForm = $this->createForm(new MediaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Media entity.
     *
     * @Route("/{id}/update", name="media_update")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Media:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Media')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new MediaType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('media_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Media entity.
     *
     * @Route("/{id}/delete", name="media_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GLZeistProgrammaBundle:Media')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Media entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('media'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
