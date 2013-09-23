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

class HoofdstukController extends Controller {

    /**
     * @Route("/hoofdstuk/", name="hoofdstuk_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $hoofdstukken = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Hoofdstuk')->findAll();
        return array(
            'hoofdstukken'=>$hoofdstukken,
            'breadcrumb'=>array(
                array(
                    'name' => 'Hoofdstukken'
                )
            )
        );
    }    
    
    /**
     * @Route("/hoofdstuk/{slug}", name="hoofdstuk")
     * @Method("GET")
     * @Template()
     */
    public function hoofdstukAction($slug)
    {
        $hoofdstuk = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Hoofdstuk')->findOneBySlug($slug);
        if (!$hoofdstuk) 
        {
            throw $this->createNotFoundException();        
        }
        $items= $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:PublishedItem')->findByHoofdstuk($hoofdstuk);
        return array(
            'hoofdstuk'=>$hoofdstuk,
            'items' => $items,
            'breadcrumb'=>array(
                array(
                    'name' => 'Home',
                    'url' => $this->generateUrl('home')
                )
                ,
                array(
                    'name' => $hoofdstuk->getTitel()
                )
            )
        );
    }    

}
