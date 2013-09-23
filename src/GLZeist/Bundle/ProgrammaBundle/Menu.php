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

    private $em;
    private $items;
    
    public function __construct(EntityManager $em,$container)
    {
        $this->em=$em;
        $this->container=$container;
        $this->items=array();
    }
            
    private function generateItems()
    {
        $router=$this->container->get('router');
        $this->items=array();
        $this->add($router->generate('home'),'Home');
        foreach($this->getHoofdstukken() as $hoofdstuk)
        {
            $this->add($router->generate('hoofdstuk',array('slug'=>$hoofdstuk->getSlug())),$hoofdstuk->getTitel());    
        }
        $this->add($router->generate('kandidaat_index'),'Kandidaten');
        return $this->items;
    }
        
    private function add($url,$titel)
    {
        $this->items[]=array('url' => $url,'titel' => $titel,'actief' => false);
    }
    
    private function maakActief($url)
    {
        foreach($this->items as $key => $item)
        {
            if($item['url']==$url)
            {
                $this->items[$key]['actief']=true;
            }
        }
    }
    
    private function getHoofdstukken()
    {
        return $this->em->getRepository('GLZeistProgrammaBundle:Hoofdstuk')->findAll();
    }
    
    public function getItems()
    {
        if(count($this->items)==0)
        {
            $this->generateItems();
        }
        $request=$this->container->get('request');    
        $this->maakActief($request->getBaseUrl().$request->getPathInfo());
        return $this->items;
    }
}
