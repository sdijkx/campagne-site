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
use GLZeist\Bundle\ProgrammaBundle\Entity\Thema;
use GLZeist\Bundle\ProgrammaBundle\Form\ThemaType;
use GLZeist\Bundle\ProgrammaBundle\Annotation\Granted;

/**
 * Thema controller.
 *
 * @Granted(role="ROLE_ADMIN")
 * @Route("/thema")
 */
class ThemaController extends Controller
{
    /**
     * Lists all Thema entities.
     *
     * @Route("/", name="admin_thema")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GLZeistProgrammaBundle:Thema')->createQueryBuilder('t')->orderBy('t.titel','ASC')->getQuery()->getResult();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Thema entity.
     *
     * @Route("/{id}/show", name="thema_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Thema')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Thema entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Thema entity.
     *
     * @Route("/new", name="thema_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Thema();
        $form   = $this->createForm(new ThemaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Thema entity.
     *
     * @Route("/create", name="thema_create")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Thema:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Thema();
        $form = $this->createForm(new ThemaType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_thema'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Thema entity.
     *
     * @Route("/{id}/edit", name="thema_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Thema')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Thema entity.');
        }

        $editForm = $this->createForm(new ThemaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Thema entity.
     *
     * @Route("/{id}/update", name="thema_update")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Thema:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Thema')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Thema entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ThemaType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('thema_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Thema entity.
     *
     * @Route("/{id}/delete", name="thema_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GLZeistProgrammaBundle:Thema')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Thema entity.');
            }
            try
            {
                $em->remove($entity);
                $em->flush();
            }
            catch(\Exception $e)
            {
                $this->get('session')->getFlashBag()->add('error','Het thema kan niet verwijderd worden');
                return $this->redirect($this->generateUrl('thema_edit',array('id'=>$id)));
            }
        }

        return $this->redirect($this->generateUrl('admin_thema'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
