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

namespace GLZeist\Bundle\ProgrammaBundle\Controller\App;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class PublishedItemController extends Controller
{
    
    /**
     * @Route("/home/items",name="item_list_home")
     * @Method("GET")
     * @Template("GLZeistProgrammaBundle:App:PublishedItem/list.html.twig")
     */    
    public function homeAction(Request $request)
    {
        $limit=$request->get('limit',5);
        $offset=$request->get('offset',0);
        $items = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:PublishedItem')->findByHomePage($limit,$offset);
        $count = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:PublishedItem')->countByHomePage($limit,$offset);
        $path=$this->generateUrl('item_list_home',array('limit'=>$limit,'offset'=> $limit+$offset));
        $moreItems=new \GLZeist\Bundle\ProgrammaBundle\MoreItems($items, $count, $limit, $offset,$path);
        return array('moreItems'=>$moreItems);

        
    }
    
    /**
     * @Route("/thema/{slug}/items",name="item_list_thema")
     * @Method("GET")
     * @Template("GLZeistProgrammaBundle:App:PublishedItem/list.html.twig")
     */    
    public function themaAction(Request $request,$slug)
    {
        $limit=$request->get('limit',5);
        $offset=$request->get('offset',0);
        $count=$this->getDoctrine()->getRepository('GLZeistProgrammaBundle:PublishedItem')->countByThema($slug);
        $items = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:PublishedItem')->findByThema($slug,$limit,$offset);
        $path=$this->generateUrl('item_list_thema',array('slug'=>$slug,'limit'=>$limit,'offset'=> $limit+$offset));
        $moreItems=new \GLZeist\Bundle\ProgrammaBundle\MoreItems($items, $count, $limit, $offset,$path);
        return array('moreItems'=>$moreItems);
    }

    /**
     * @Route("/trefwoord/{slug}/items",name="item_list_trefwoord")
     * @Method("GET")
     * @Template("GLZeistProgrammaBundle:App:PublishedItem/list.html.twig")
     */    
    public function trefwoordAction(Request $request,$slug)
    {
        $limit=$request->get('limit',5);
        $offset=$request->get('offset',0);
        $items = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:PublishedItem')->findByTrefwoord($slug,$limit,$offset);
        $count = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:PublishedItem')->countByTrefwoord($slug,$limit,$offset);
        $path=$this->generateUrl('item_list_trefwoord',array('slug'=>$slug,'limit'=>$limit,'offset'=> $limit+$offset));
        $moreItems=new \GLZeist\Bundle\ProgrammaBundle\MoreItems($items, $count, $limit, $offset,$path);
        return array('moreItems'=>$moreItems);
    }
    
    
    /**
     * @Route("/{hoofdstuk}/{slug}",name="item")
     * @Method("GET")
     * @Template()
     */
    public function detailAction($hoofdstuk,$slug)
    {
        $item = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:PublishedItem')->findOneBySlug($slug);
        if (!$item) 
        {
            throw $this->createNotFoundException();        
        }
        $breadcrumb=$this->createBreadcrumb($item);
        return array(
            'item'=>$item,
            'hoofdstuk'=> $item->getHoofdstuk(),
            'breadcrumb'=> $breadcrumb
        );
    }
    private function createBreadcrumb($item)
    {
        $hoofdstuk=$item->getHoofdstuk();
        if($hoofdstuk)
        {
            return array(
                array(
                    'url' => $this->generateUrl('hoofdstuk',array('slug'=>$hoofdstuk)),
                    'name' => $item->getHoofdstuk()->getTitel()
                ),
                array(
                    'name' => $item->getTitel()
                )
            );            
        }
        else
        {
            return array(
                array(
                    'url' => $this->generateUrl('home'),
                    'name' => 'Home'
                ),
                array(
                    'name' => $item->getTitel()
                )
            );            
        }
    }
}
