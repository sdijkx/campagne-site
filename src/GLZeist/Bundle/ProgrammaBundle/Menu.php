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
namespace GLZeist\Bundle\ProgrammaBundle;

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\Routing\RouterInterface;
use \Doctrine\ORM\EntityManager;

class Menu {

    private $router;
    private $request;
    private $em;
    private $url;
    private $items;
    
    public function __construct(EntityManager $em)
    {
        $this->em=$em;
    }
    
    public function setRequest(Request $request) {
        $this->request = $request;
        $this->items=array();
    }
    
    public function setRouter($router) {
        $this->router = $router;
    }
        
    public function generateItems()
    {
        if(count($this->items)==0)
        {
            $this->url=$this->request->getBaseUrl().$this->request->getPathInfo();
            $this->add($this->router->generate('home'),'Home');
            foreach($this->getHoofdstukken() as $hoofdstuk)
            {
                $this->add($this->router->generate('hoofdstuk',array('slug'=>$hoofdstuk->getSlug())),$hoofdstuk->getTitel());    
            }
            $this->add($this->router->generate('kandidaat_index'),'Kandidaten');
        }
        return $this->items;
    }
        
    private function add($url,$titel)
    {
        $this->items[]=array('url' => $url,'titel' => $titel,'actief' => ($url==$this->url));
    }
    
    private function getHoofdstukken()
    {
        return $this->em->getRepository('GLZeistProgrammaBundle:Hoofdstuk')->findAll();
    }
    
    public function getItems()
    {
        return $this->items;
    }
}
