<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Rule
 *
 * @author steven
 */
namespace GLZeist\Bundle\ProgrammaBundle\Listener\Rule;
use \Symfony\Component\HttpKernel\Event\FilterControllerEvent;


abstract class Rule {
    
    private $next;
    
    abstract public function isValid(FilterControllerEvent $event);
    
    public function __construct($next=null) {
        $this->next=$next;
    } 
    
    public function apply(FilterControllerEvent $event) {
        return $this->isValid($event) && $this->applyNext($event);
    }
    
    private function applyNext(FilterControllerEvent $event) {
        if($this->next instanceof Rule) {
            return $this->next->apply();
        } 
        return true;
    }
}
