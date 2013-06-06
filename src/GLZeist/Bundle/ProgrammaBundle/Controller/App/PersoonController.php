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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class PersoonController extends Controller 
{
    /**
     * @Route("/personen", name="persoon_index")
     * @Template()
     */
    public function indexAction()
    {
        $personen = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Persoon')->findAll();
        return array('personen' => $personen);
    }    
    /**
     * @Route("/persoon/{slug}", name="persoon")
     * @Template()
     */
    public function detailAction($slug)
    {
        $persoon = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Persoon')->findOneBySlug($slug);
        if(is_null($persoon))
        {
            throw $this->createNotFoundException();
        }
        return array(
            'persoon' => $persoon,
            'breadcrumb'=>array(
                array(
                    'url' => $this->generateUrl('persoon_index'),
                    'name' => 'Personen'
                ),
                array(
                    'name' => $persoon->getNaam()
                )
            )
            
            );
    }    
    
}
