<?php
namespace GLZeist\Bundle\ProgrammaBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use GLZeist\Bundle\ProgrammaBundle\Entity\Speerpunt;
use GLZeist\Bundle\ProgrammaBundle\Form\SpeerpuntType;
use GLZeist\Bundle\ProgrammaBundle\Annotation\Granted;

/**
 * Relatie controller.
 * 
 * @Granted(role="ROLE_MODERATOR")
 * @Route("/speerpunt")
 */
class SpeerpuntController extends Controller {

    /**
     * Lists all Speerpunt entities.
     *
     * @Route("/", name="admin_speerpunt")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GLZeistProgrammaBundle:Speerpunt')->createQueryBuilder('t')->orderBy('t.titel','ASC')->getQuery()->getResult();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Speerpunt entity.
     *
     * @Route("/{id}/show", name="speerpunt_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Speerpunt')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Speerpunt entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Speerpunt entity.
     *
     * @Route("/new", name="speerpunt_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Speerpunt();
        $form   = $this->createForm(new SpeerpuntType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Speerpunt entity.
     *
     * @Route("/create", name="speerpunt_create")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Speerpunt:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Speerpunt();
        $form = $this->createForm(new SpeerpuntType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_speerpunt'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Speerpunt entity.
     *
     * @Route("/{id}/edit", name="speerpunt_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Speerpunt')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Speerpunt entity.');
        }

        $editForm = $this->createForm(new SpeerpuntType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Speerpunt entity.
     *
     * @Route("/{id}/update", name="speerpunt_update")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Speerpunt:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Speerpunt')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Speerpunt entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new SpeerpuntType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('speerpunt_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Speerpunt entity.
     *
     * @Route("/{id}/delete", name="speerpunt_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GLZeistProgrammaBundle:Speerpunt')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Speerpunt entity.');
            }
            try
            {
                $em->remove($entity);
                $em->flush();
            }
            catch(\Exception $e)
            {
                $this->get('session')->getFlashBag()->add('error','Het speerpunt kan niet verwijderd worden');
                return $this->redirect($this->generateUrl('speerpunt_edit',array('id'=>$id)));
            }
        }

        return $this->redirect($this->generateUrl('admin_speerpunt'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
}

