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
class RequestListener 
{
    private $reader;
    private $securityContext;
    
    public function __construct($reader,SecurityContextInterface $securityContext)
    {
        $this->reader = $reader;
        $this->securityContext=$securityContext;
    }    
    public function onKernelController(\Symfony\Component\HttpKernel\Event\FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController()))
        { //return if no controller
            return;
        }

        $object = new \ReflectionObject($controller[0]); // get controller
        $method = $object->getMethod($controller[1]); // get method
        $granted=true;
        
        foreach ($this->reader->getClassAnnotations($object) as $configuration)
        {
            if ($configuration instanceof \GLZeist\Bundle\ProgrammaBundle\Annotation\Granted)
            {
                $granted=$this->isGranted($configuration->role);
            }
        }

        foreach ($this->reader->getMethodAnnotations($method) as $configuration)
        { //Start of annotations reading

            if ($configuration instanceof \GLZeist\Bundle\ProgrammaBundle\Annotation\Granted)
            {
                $granted=$this->isGranted($configuration->role);
            }
        }
        if(!$granted)
        {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }
        
    }
    
    private function isGranted($role)
    {
        return $this->securityContext->isGranted($role);
    }
    
}
