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
     * @Route("/", name="admin_trefwoord")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->createQuery('SELECT t FROM GLZeistProgrammaBundle:Trefwoord t ORDER BY t.trefwoord ASC')->getResult();

        return array(
            'entities' => $entities,
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

            return $this->redirect($this->generateUrl('admin_trefwoord', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
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
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('GLZeistProgrammaBundle:Trefwoord')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Trefwoord entity.');
        }

        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('admin_trefwoord'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
