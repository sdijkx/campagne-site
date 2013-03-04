<?php

namespace GLZeist\Bundle\ProgrammaBundle\Controller\Admin;

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
 * @Route("/hoofdstuk")
 */
class HoofdstukController extends Controller
{
    /**
     * Lists all Hoofdstuk entities.
     *
     * @Route("/", name="hoofdstuk")
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
     * Finds and displays a Hoofdstuk entity.
     *
     * @Route("/{id}/show", name="hoofdstuk_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Hoofdstuk')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Hoofdstuk entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
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

            return $this->redirect($this->generateUrl('hoofdstuk', array('id' => $entity->getId())));
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

        return $this->redirect($this->generateUrl('hoofdstuk'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
