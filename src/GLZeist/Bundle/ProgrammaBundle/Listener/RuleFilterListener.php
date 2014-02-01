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

namespace GLZeist\Bundle\ProgrammaBundle\Listener;
use Symfony\Component\Security\Core\SecurityContextInterface;
class RuleFilterListener 
{
    
    private $rule;
    
    public function __construct(Rule\Rule $rule)
    {
        $this->rule=$rule;
    }    
    
    public function onKernelRequest(\Symfony\Component\HttpKernel\Event\GetResponseEvent $event) {
        
        
    }
    
    public function onKernelController(\Symfony\Component\HttpKernel\Event\FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController()))
        { //return if no controller
            return;
        }
        
        if(!$this->rule->apply($event)) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }
    }
        
}
