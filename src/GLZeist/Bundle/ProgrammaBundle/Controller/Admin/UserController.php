<?php

namespace GLZeist\Bundle\ProgrammaBundle\Controller\Admin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;

class UserController extends Controller
{
    /**
     * @Route("/login")
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
        
        if($securityContext->isGranted(\Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter::IS_AUTHENTICATED_FULLY))
        {
            return $this->redirect($this->generateUrl('hoofdstuk'));
        }
        
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) 
        {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } 
        else 
        {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        
        return array(
            'error'=>$error,
            'last_username'=>$session->get(SecurityContext::LAST_USERNAME)
        );
        
    }
    /**
     * @Route("/logout")
     * @Template
     */
    
    public function logoutAction()
    {
        $this->get('session')->invalidate();
        return $this->generateUrl('/');        
    }
    
}