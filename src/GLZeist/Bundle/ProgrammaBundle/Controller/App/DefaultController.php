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

use GLZeist\Bundle\ProgrammaBundle\Entity\Speerpunt;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function homeAction()
    {
        
        //$speerpunten = array();
        
        $speerpunten=$this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Speerpunt')->findRandomForHomePage();
        $items = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:PublishedItem')->findAllForHomePage();
        $hoofdstukken = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Hoofdstuk')->findAll();
        $rss=$this->get('gl_zeist_programma.rss');
        $nieuws=$rss->getItems(5);
        return array('items' => $items,'speerpunten' => $speerpunten,'hoofdstukken'=>$hoofdstukken,'nieuws' => $nieuws);
    }
    
    /**
     * @Route("/zoeken", name="glzeist_programma_app_search" )
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
    
    private function moreitems($results,$limit)
    {
        if(!is_null($results) && count($results)>$limit)
        {
            array_pop($results);
            return true;
        }
         else 
        {
             return false;
        }        
    }
    
    /**
     * @Route("/thema/{slug}", name="thema")
     * @Template()
     */
    public function themaAction($slug)
    {
        $thema = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Thema')->findOneBySlug($slug);
        if (!$thema) 
        {
            throw $this->createNotFoundException();        
        }
        $limit=$this->getRequest()->get('limit',10);
        $items=$this->getDoctrine()->getRepository('GLZeistProgrammaBundle:PublishedItem')->findByThema($thema,array('gepubliceerdOp'=>'DESC'),$limit+1);
        return array(
            'thema'=>$thema,
            'items'=>$items,
            'limit'=>$limit,
            'moreitems'=>$this->moreitems($items,$limit)
        );
    }
    
    /**
     * @Route("/trefwoord/{slug}", name="trefwoord")
     * @Template()
     */
    public function trefwoordAction($slug)
    {
        $trefwoord = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Trefwoord')->findOneBySlug($slug);
        if (!$trefwoord) 
        {
            throw $this->createNotFoundException();        
        }
        $limit=$this->getRequest()->get('limit',10);
        $items=$this->getDoctrine()->getRepository('GLZeistProgrammaBundle:PublishedItem')->findAllForTrefwoord($trefwoord,array('gepubliceerdOp'=>'DESC'),$limit+1);
        return array(
            'trefwoord'=>$trefwoord,
            'items'=>$items, 
            'limit'=>$limit,
            'moreitems'=>$this->moreitems($items,$limit),
            'breadcrumb'=>array(
                array(
                    'name' => $trefwoord->getTrefwoord()
                )
            )
        );
    }
    
    
    /**
     * @Route("/file/{filename}",name="file") 
     */
    public function fileAction($filename)
    {
        $dir=$this->container->getParameter('upload_dir');
        $path=$dir.DIRECTORY_SEPARATOR.$filename;
        $guesser=  \Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser::getInstance();
        $mimeType=$guesser->guess($path);
        ob_clean();
        header('Content-type: '.$mimeType);
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        readfile($path);
        exit;
        
    }
    
    /**
     * @Route("/site/banner",name="site_banner") 
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
        header('Content-type: '.$mimeType);
        readfile($path);
        exit;
        
    }
    
    
    
}
