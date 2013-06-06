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
use GLZeist\Bundle\ProgrammaBundle\Entity\Relatie;
use GLZeist\Bundle\ProgrammaBundle\Form\RelatieType;
use GLZeist\Bundle\ProgrammaBundle\Annotation\Granted;

/**
 * Relatie controller.
 * 
 * @Granted(role="ROLE_ADMIN")
 * @Route("/relatie")
 */
class RelatieController extends Controller
{
    /**
     * Lists all Relatie entities.
     *
     * @Route("/", name="relatie")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GLZeistProgrammaBundle:Relatie')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Relatie entity.
     *
     * @Route("/{id}/show", name="relatie_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Relatie')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Relatie entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Relatie entity.
     *
     * @Route("/new", name="relatie_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Relatie();
        $form   = $this->createForm(new RelatieType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Relatie entity.
     *
     * @Route("/create", name="relatie_create")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Relatie:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Relatie();
        $form = $this->createForm(new RelatieType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('relatie_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Relatie entity.
     *
     * @Route("/{id}/edit", name="relatie_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Relatie')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Relatie entity.');
        }

        $editForm = $this->createForm(new RelatieType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Relatie entity.
     *
     * @Route("/{id}/update", name="relatie_update")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Relatie:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Relatie')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Relatie entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new RelatieType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('relatie_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Relatie entity.
     *
     * @Route("/{id}/delete", name="relatie_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GLZeistProgrammaBundle:Relatie')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Relatie entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('relatie'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
