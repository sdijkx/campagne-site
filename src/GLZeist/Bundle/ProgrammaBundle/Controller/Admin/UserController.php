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
use Symfony\Component\Security\Core\SecurityContext;
use GLZeist\Bundle\ProgrammaBundle\Annotation\Granted;
use GLZeist\Bundle\ProgrammaBundle\Form\UserType;
use GLZeist\Bundle\ProgrammaBundle\Entity\User;
/**
 * @Granted(role="ROLE_ADMIN")
 */
class UserController extends Controller
{
    /**
     * @Route("/login")
     * @Granted(role="IS_AUTHENTICATED_ANONYMOUSLY")
     * @Template
     */
    
    public function loginAction()
    {
        $request=$this->getRequest();
        $session=$request->getSession();
        if($request->isXmlHttpRequest())
        {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }
        
        $securityContext=$this->get('security.context');
        $token=$securityContext->getToken();
        if(empty($token))
        {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }
        
        if($securityContext->isGranted(\Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED))
        {
            return $this->redirect($this->generateUrl('admin_item'));
        }
        
        $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        // get the login error if there is one
        if ($error) 
        {
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
            $this->get('session')->getFlashBag()->add('error','U kan niet aangemeld worden');
        } 
        
        
        
        return array(
            'last_username'=>$session->get(SecurityContext::LAST_USERNAME)
        );
        
    }
    /**
     * @Route("/logout")
     * @Granted(role="ROLE_USER")
     * @Template
     */
    
    public function logoutAction()
    {
        $this->get('session')->invalidate();
        return $this->generateUrl('/');        
    }
    
    
    /**
     * @Route("/user/index",name="admin_user")
     * @Template
     */

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->createQuery('SELECT u FROM GLZeistProgrammaBundle:User u ORDER BY u.username ASC')->getResult();

        return array(
            'entities' => $entities,
        );
    }
    
    
    
    /**
     * @Route("/user/new", name="user_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new User();
        $form   = $this->createForm(new UserType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    
    
    /**
     *
     * @Route("/create", name="user_create")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:User:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new User();
        $form = $this->createForm(new UserType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            try
            {
                $encoderFactory=$this->get('security.encoder_factory');
                $entity->setPassword($this->encodePassword($entity));
                
                $em->persist($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice','De gebruiker is toegevoegd');
                return $this->redirect($this->generateUrl('admin_user'));
            }
            catch(\Exception $e)
            {
                $this->get('session')->getFlashBag()->add('error','De gebruiker kan niet worden toegevoegd');
            }
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    
    
    /**
     * @Route("/user/{id}/edit",name="user_edit")
     * @Template
     */

    public function editAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:User')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }        
        
        $editForm = $this->createForm(new UserType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
        return array(
            'entity' => $entity,
        );
    }
    
    /**
     *
     * @Route("/user/{id}/update", name="user_update")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Admin/User:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GLZeistProgrammaBundle:User')->find($id);
        $password=$entity->getPassword();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new UserType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            try
            {
                $updatedPassword=$entity->getPassword();
                if(empty($updatedPassword))
                {
                    $entity->setPassword($password);
                }
                else 
                {
                    $entity->setPassword($this->encodePassword($entity));
                }
                $em->persist($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice','De gebruiker is bijgewerkt');
                return $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
            }
            catch(\Exception $e)
            {
                echo $e->getMessage();exit;
                $this->get('session')->getFlashBag()->add('error','De gebruiker kan niet worden bijgewerkt');                
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
     * @Route("/user/{id}/delete", name="user_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GLZeistProgrammaBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }
            try
            {
                $em->remove($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('error','De gebruiker is verwijderd');
            }
            catch(\Exception $e)
            {
                $this->get('session')->getFlashBag()->add('error','De gebruiker kan niet verwijderd worden');
                return $this->redirect($request->headers->get('Referer'));
            }
        }

        return $this->redirect($this->generateUrl('admin_user'));
    }    
    
    
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    private function encodePassword(User $user)
    {
                
        $encoderFactory=$this->get('security.encoder_factory');
        return $encoderFactory->getEncoder($user)->encodePassword($user->getPassword(),$user->getSalt());
        
    }
    
}