<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminControllerIpFilter
 *
 * @author steven
 */
namespace GLZeist\Bundle\ProgrammaBundle\Listener\Rule;
use \Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\Request;


class AdminAllowIp extends Rule {
    
    private $ips=array();

    public function __construct($ips,$next=null) {
        parent::__construct($next);
        
        $this->ips=$ips;
    }
    
    public function isValid(FilterControllerEvent $event) {
     
        $controller=$event->getController();
        if($this->isAdminController($controller[0])) {
            return $this->isAllowed($event->getRequest());
        }
        return true;
    }
    
    private function isAllowed(Request $request) {
        return in_array($request->getClientIp(),$this->ips);
    }
    
    private function isAdminController($object)
    {
        return $this->classInNamespace("GLZeist\\Bundle\\ProgrammaBundle\\Controller\\Admin",$object);
    }
    
    private function classInNamespace($namespace,$object) {
        return (is_object($object) && ($namespace=='' || $this->startsWith($namespace,get_class($object))));
    }    
    
    private function startsWith($needle,$haystack) {
        return substr($haystack,0,strlen($needle)) == $needle;
    }
}
