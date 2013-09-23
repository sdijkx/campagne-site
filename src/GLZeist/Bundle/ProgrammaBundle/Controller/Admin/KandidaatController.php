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
use GLZeist\Bundle\ProgrammaBundle\Entity\Kandidaat;
use GLZeist\Bundle\ProgrammaBundle\Form\KandidaatType;
use GLZeist\Bundle\ProgrammaBundle\Annotation\Granted;

/**
 * Kandidaat controller.
 * @Granted(role="ROLE_MODERATOR")
 * @Route("/kandidaat")
 */

class KandidaatController extends Controller 
{
    /**
     * Lists all Item entities.
     *
     * @Route("/", name="admin_kandidaat")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GLZeistProgrammaBundle:Kandidaat')->createQueryBuilder('k')->orderBy('k.plek','ASC')->getQuery()->getResult();

        return array(
            'entities' => $entities,
        );        
    }
    
    /**
     *
     * @Route("/new", name="admin_new_kandidaat")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Kandidaat();
        $form   = $this->createForm(new KandidaatType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );        
    }    
    
    /**
     *
     * @Route("/create", name="admin_create_kandidaat")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Admin:Kandidaat/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new \GLZeist\Bundle\ProgrammaBundle\Entity\Kandidaat();
        $form = $this->createForm(new KandidaatType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            
            try
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice','De kandidaat is toegevoegd');
                return $this->redirect($this->generateUrl('admin_kandidaat', array('id' => $entity->getId())));
            }
            catch(\Exception $e)
            {
                $this->get('session')->getFlashBag()->add('error','De kandidaat kan niet worden toegevoegd');
            }
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );        
    }
    
    /**
     *
     * @Route("/{id}/edit", name="admin_edit_kandidaat")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Kandidaat')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Kandidaat entity.');
        }

        $editForm = $this->createForm(new KandidaatType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );        
    }
    
    /**
     *
     * @Route("/{id}/update", name="admin_update_kandidaat")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Admin:Kandidaat/edit.html.twig")
     */
    public function updateAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:Kandidaat')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Kandidaat entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new KandidaatType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            
            try
            {
                if($entity->getFile()!==null)
                {
                    $entity->setImagefile(rand());
                    $entity->setThumbfile(rand());
                }

                $em->persist($entity);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('notice','De kandidaat is opgeslagen');                

                return $this->redirect($this->generateUrl('admin_edit_kandidaat', array('id' => $id)));
            }
            catch(\Exception $e)
            {
                $this->get('session')->getFlashBag()->add('error','De kandidaat kan niet worden opgeslagen');                
            }
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );        
    }
    
    
    /**
     *
     * @Route("/{id}/delete", name="admin_delete_kandidaat")
     * @Method("POST")
     * @Template()
     */
    public function deleteAction(Request $request,$id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GLZeistProgrammaBundle:Kandidaat')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Kandidaat entity.');
            }
            
            try
            {
                $em->remove($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice','De kandidaat is verwijderd');                
                return $this->redirect($this->generateUrl('admin_kandidaat'));        
            }
            catch(\Exception $e)
            {
                $this->get('session')->getFlashBag()->add('error','De kandidaat kan niet worden verwijderd');                                
            }
        }

        return $this->redirect($this->generateUrl('admin_edit_kandidaat',array('id'=>$id)));        
    }
    
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
}