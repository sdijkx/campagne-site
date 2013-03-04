<?php

namespace GLZeist\Bundle\ProgrammaBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use GLZeist\Bundle\ProgrammaBundle\Entity\Trefwoord;
use GLZeist\Bundle\ProgrammaBundle\Form\TrefwoordType;

/**
 * Trefwoord controller.
 *
 * @Route("/trefwoord")
 */
class TrefwoordController extends Controller
{
    /**
     * Lists all Trefwoord entities.
     *
     * @Route("/", name="trefwoord")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GLZeistProgrammaBundle:Trefwoord')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Trefwoord entity.
     *
     * @Route("/{id}/show", name="trefwoord_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Trefwoord')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Trefwoord entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Trefwoord entity.
     *
     * @Route("/new", name="trefwoord_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Trefwoord();
        $form   = $this->createForm(new TrefwoordType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Trefwoord entity.
     *
     * @Route("/create", name="trefwoord_create")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Trefwoord:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Trefwoord();
        $form = $this->createForm(new TrefwoordType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('trefwoord', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Trefwoord entity.
     *
     * @Route("/{id}/edit", name="trefwoord_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Trefwoord')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Trefwoord entity.');
        }

        $editForm = $this->createForm(new TrefwoordType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Trefwoord entity.
     *
     * @Route("/{id}/update", name="trefwoord_update")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Trefwoord:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Trefwoord')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Trefwoord entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new TrefwoordType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('trefwoord_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Trefwoord entity.
     *
     * @Route("/{id}/delete", name="trefwoord_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GLZeistProgrammaBundle:Trefwoord')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Trefwoord entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('trefwoord'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
