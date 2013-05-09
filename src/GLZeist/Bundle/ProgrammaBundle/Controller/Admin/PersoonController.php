<?php

namespace GLZeist\Bundle\ProgrammaBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use GLZeist\Bundle\ProgrammaBundle\Entity\Persoon;
use GLZeist\Bundle\ProgrammaBundle\Form\PersoonType;
use GLZeist\Bundle\ProgrammaBundle\Annotation\Granted;

/**
 * Persoon controller.
 * @Granted(role="ROLE_MODERATOR")
 * @Route("/persoon")
 */

class PersoonController extends Controller 
{
    /**
     * Lists all Item entities.
     *
     * @Route("/", name="admin_persoon")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GLZeistProgrammaBundle:Persoon')->findAll();

        return array(
            'entities' => $entities,
        );        
    }
    
    /**
     *
     * @Route("/new", name="admin_new_persoon")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Persoon();
        $form   = $this->createForm(new PersoonType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );        
    }    
    
    /**
     *
     * @Route("/create", name="admin_create_persoon")
     * @Method("POST")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $entity  = new \GLZeist\Bundle\ProgrammaBundle\Entity\Persoon();
        $form = $this->createForm(new PersoonType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_persoon', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );        
    }
    
    /**
     *
     * @Route("/{id}/edit", name="admin_edit_persoon")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Persoon')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Persoon entity.');
        }

        $editForm = $this->createForm(new PersoonType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );        
    }
    
    /**
     *
     * @Route("/{id}/update", name="admin_update_persoon")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Persoon:edit.html.twig")
     */
    public function updateAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Persoon')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Persoon entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new PersoonType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            
            if($entity->getFile()!==null)
            {
                $entity->setImagefile(rand());
                $entity->setThumbfile(rand());
            }
            
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_edit_persoon', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );        
    }
    
    
    /**
     *
     * @Route("/{id}/delete", name="admin_delete_persoon")
     * @Method("POST")
     * @Template()
     */
    public function deleteAction(Request $request,$id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GLZeistProgrammaBundle:Persoon')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Persoon entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_persoon'));        
    }
    
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
}