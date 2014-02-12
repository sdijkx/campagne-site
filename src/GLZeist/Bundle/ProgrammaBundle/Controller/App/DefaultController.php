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

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Method("GET")
     * @Template()
     */
    public function homeAction()
    {
        
        //$speerpunten = array();
        
        $speerpunten=$this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Speerpunt')->findRandomForHomePage();
        $hoofdstukken = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Hoofdstuk')->findAll();
        return array('speerpunten' => $speerpunten,'hoofdstukken'=>$hoofdstukken);
    }
    
    /**
     * @Route("/zoeken", name="glzeist_programma_app_search" )
     * @Method("POST")
     * @Template()
     */
    
    public function searchAction()
    {
        $ft=$this->get('gl_zeist_programma.fulltext_search_service');
        $search=$this->getRequest()->get('search');
        $limit=$this->getRequest()->get('limit',100);
        $results = $ft->search($search,$limit);
        return array('results'=>$results,'search'=>$search,'limit'=>$limit,
            'breadcrumb'=>array(
                array(
                    'name' => 'Zoekresultaten'
                )
            )
            
            );
    }
    
        
    /**
     * @Route("/trefwoord/{slug}", name="trefwoord")
     * @Method("GET") 
     * @Template()
     */
    public function trefwoordAction($slug)
    {
        $trefwoord = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Trefwoord')->findOneBySlug($slug);
        if (!$trefwoord) 
        {
            throw $this->createNotFoundException();        
        }
        $trefwoordService=$this->get('gl_zeist_programma.trefwoord_service');
        $results=$trefwoordService->search($trefwoord->getId());
        return array(
            'trefwoord'=>$trefwoord,
            'results'=>$results, 
            'breadcrumb'=>array(
                array(
                    'name' => $trefwoord->getTrefwoord()
                )
            )
        );
    }
    
    
    /**
     * @Route("/file/{filename}",name="file") 
     * @Method("GET")
     */
    public function fileAction($filename)
    {
        $dir=$this->container->getParameter('upload_dir');
        $path=$dir.DIRECTORY_SEPARATOR.$filename;
        $guesser=  \Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser::getInstance();
        $mimeType=$guesser->guess($path);
        ob_clean();
        header("Cache-Control: public"); // HTTP/1.1
        header("Expires: ".date('r',strtotime('+24 hours'))); // Date in the past        
        header('Content-type: '.$mimeType);
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        readfile($path);
        exit;
        
    }
    
    /**
     * @Route("/site/banner",name="site_banner") 
     * @Method("GET")
     */
    public function bannerAction()
    {
        $site=$this->get('gl_zeist_programma.site');
        if(!$site->getBanner() instanceof \Symfony\Component\HttpFoundation\File\File)
        {
            return new \Symfony\Component\HttpFoundation\Response('Geen Banner',404);
        }
        $path=$site->getBanner()->getRealPath();
        $guesser=  \Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser::getInstance();
        $mimeType=$guesser->guess($path);
        ob_clean();
        header("Cache-Control: public"); // HTTP/1.1
        header("Expires: ".date('r',strtotime('+24 hours'))); // Date in the past                
        header('Content-type: '.$mimeType);
        readfile($path);
        exit;
        
    }
    
    
    
}
