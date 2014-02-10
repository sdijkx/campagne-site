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

use GLZeist\Bundle\ProgrammaBundle\Entity\Speerpunt;


class WijkController extends Controller {
    
    
    /**
     * @Route("/wijk/", name="wijk_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        
        $wijken = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Wijk')->findAll();
        if (!$wijken) 
        {
            throw $this->createNotFoundException();        
        }
        return array(
            'wijken'=>$wijken
        );
    }    
    /**
     * @Route("/wijk/{slug}/", name="wijk_detail")
     * @Method("GET")
     * @Template()
     */
    public function wijkAction($slug) {
        
        $wijk = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Wijk')->findOneBySlug($slug);
        if (!$wijk) 
        {
            throw $this->createNotFoundException();        
        }
        $this->get('gl_zeist_programma.site')->getMenu()->maakActief(
                $this->generateUrl('wijk_index')
        );
        
        return array(
            'wijk'=>$wijk,
            'breadcrumb'=>array(
                array(
                    'url' => $this->generateUrl('wijk_index'),
                    'name' => 'Wijken'
                ),
                array(
                    'name' => $wijk->getWijk()
                )
            )
        );
    }
    
    /**
     * @Route("/wijk/{slug}/afbeelding/{afbeeldingId}",name="wijk_afbeelding")
     * @Method("GET")
     * @Template()
     */
    public function afbeeldingAction($slug,$afbeeldingId) {

        $wijk = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Wijk')->findOneBySlug($slug);
        if (!$wijk) 
        {
            throw $this->createNotFoundException();        
        }
        
        $afbeeldingenService=$this->get('gl_zeist_programma.afbeeldingen_service');
        try {
            $afbeeldingenService->afbeeldingNaarStdout($wijk,$afbeeldingId);
        }
        catch(\Exception $e) {
            throw $this->createNotFoundException($e);        
        }
        exit;
        
    }    
    
    /**
     * @Route("/wijk/{slug}/wijkparel/{wijkParelId}/afbeelding/{afbeeldingId}",name="wijkparel_afbeelding")
     * @Method("GET")
     * @Template()
     */
    public function wijkParelAfbeeldingAction($slug,$wijkParelId,$afbeeldingId) {

        $wijkParel = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:WijkParel')->findOneBySlug($wijkParelId);
        if (!$wijkParel) 
        {
            throw $this->createNotFoundException();        
        }
        
        $afbeeldingenService=$this->get('gl_zeist_programma.afbeeldingen_service');
        try {
            $afbeeldingenService->afbeeldingNaarStdout($wijkParel,$afbeeldingId);
        }
        catch(\Exception $e) {
            throw $this->createNotFoundException($e);        
        }
        exit;
        
    }    
    
}
