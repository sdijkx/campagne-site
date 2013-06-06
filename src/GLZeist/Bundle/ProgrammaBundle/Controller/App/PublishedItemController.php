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

class PublishedItemController extends Controller
{
        
    
    /**
     * @Route("/{thema}/{slug}",name="item")
     * @Template()
     */
    public function detailAction($thema,$slug)
    {
        $item = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:PublishedItem')->findOneBySlug($slug);
        if (!$item) 
        {
            throw $this->createNotFoundException();        
        }
        return array(
            'item'=>$item,
            'thema'=> $item->getThema(),
            'breadcrumb'=>array(
                array(
                    'url' => $this->generateUrl('thema',array('slug'=>$thema)),
                    'name' => $item->getThema()->getTitel()
                ),
                array(
                    'name' => $item->getTitel()
                )
            )
        );
    }
    
    
    
    
}
