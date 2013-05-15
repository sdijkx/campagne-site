<?php

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
        
        $speerpunten = array();
        
        $i=0;

        $speerpunten[$i]=new Speerpunt;
        $speerpunten[$i]->setTitel('Repaircafé');
        $speerpunten[$i]->setContent('Repaircafé groot succes');
        $speerpunten[$i]->setAfbeelding('http://zeist.groenlinks.nl/files/imageupload/sticky_teaser_image_1/repaircaf___Peter.jpg');
        $speerpunten[$i]->setUrl('http://zeist.groenlinks.nl/node/92906');

        $i++;
        $speerpunten[$i]=new Speerpunt;
        $speerpunten[$i]->setTitel('Kinderpardon');
        $speerpunten[$i]->setContent('Raad Zeist steunt kinderpardon');
        $speerpunten[$i]->setAfbeelding('http://zeist.groenlinks.nl/files/imageupload/sticky_teaser_image_1/Laura_raad_over_motie_KP.jpg');
        $speerpunten[$i]->setUrl('http://zeist.groenlinks.nl/node/79414');

        $i++;
        $speerpunten[$i]=new Speerpunt;
        $speerpunten[$i]->setTitel('Soesterberg');
        $speerpunten[$i]->setContent('Zorgen Groenlinks over Arena Nationaal militair museum');
        $speerpunten[$i]->setAfbeelding('http://zeist.groenlinks.nl/files/imageupload/sticky_teaser_image_1/Arena_NMM.jpg');
        $speerpunten[$i]->setUrl('http://zeist.groenlinks.nl/node/93317');

        $i++;
        $speerpunten[$i]=new Speerpunt;
        $speerpunten[$i]->setTitel('Kerckebosch');
        $speerpunten[$i]->setContent('Vernieuwing Kerckebosch van start gegaan');
        $speerpunten[$i]->setAfbeelding('http://zeist.groenlinks.nl/files/imageupload/sticky_teaser_image_1/map_home.jpg');
        $speerpunten[$i]->setUrl('http://zeist.groenlinks.nl/node/80117');
        
        $items = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:PublishedItem')->findAllForHomePage();
        return array('items' => $items,'speerpunten' => $speerpunten);
    }
    
    /**
     * @Route("/zoeken", name="glzeist_programma_app_search" )
     * @Template()
     */
    
    public function searchAction()
    {
        $ft=$this->get('gl_zeist_programma.fulltext_search_service');
        $search=$this->getRequest()->get('search');
        $limit=$this->getRequest()->get('limit',10);
        $results = $ft->search($search,$limit+1);
        return array('results'=>$results,'search'=>$search,'limit'=>$limit,'moreitems'=>$this->moreitems($results,$limit),
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
    
    
}
