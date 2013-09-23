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


class KandidaatController extends Controller 
{
    /**
     * @Route("/kandidaten", name="kandidaat_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $kandidaten = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Kandidaat')->findAll(array(),array('plek'));
        return array('kandidaten' => $kandidaten);
    }    
    /**
     * @Route("/kandidaat/{slug}", name="kandidaat")
     * @Method("GET")
     * @Template()
     */
    public function detailAction($slug)
    {
        $kandidaat = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Kandidaat')->findOneBySlug($slug);
        if(is_null($kandidaat))
        {
            throw $this->createNotFoundException();
        }
        return array(
            'kandidaat' => $kandidaat,
            'breadcrumb'=>array(
                array(
                    'url' => $this->generateUrl('kandidaat_index'),
                    'name' => 'Kandidaten'
                ),
                array(
                    'name' => $kandidaat->getNaam()
                )
            )
            
            );
    }    
    
}
