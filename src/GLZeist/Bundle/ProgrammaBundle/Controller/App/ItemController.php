<?php

namespace GLZeist\Bundle\ProgrammaBundle\Controller\App;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ItemController extends Controller
{
    
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
     * @Route("/zoeken", name="glzeist_programma_app_item_search")
     * @Template()
     */
    public function searchAction()
    {
        $search=$this->getRequest()->get('search');
        $limit=$this->getRequest()->get('limit',10);
        $results = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Item')->findAllForSearch($search,$limit+1);
        return array('results'=>$results,'search'=>$search,'limit'=>$limit,'moreitems'=>$this->moreitems($results,$limit),
            'breadcrumb'=>array(
                array(
                    'name' => 'Zoeken'
                )
            )
            
            );
    }
    

    /**
     * @Route("/{slug}", name="thema")
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
        $items=$this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Item')->findByThema($thema,array('datumtijd'=>'DESC'),$limit+1);
        return array('thema'=>$thema,'items'=>$items,'limit'=>$limit,'moreitems'=>$this->moreitems($items,$limit)
            
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
        $items=$this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Item')->findAllForTrefwoord($trefwoord,array('datumtijd'=>'DESC'),$limit+1);
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
     * @Template()
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
        
    }
        
    
    /**
     * @Route("/{thema}/{slug}",name="item")
     * @Template()
     */
    public function detailAction($thema,$slug)
    {
        $item = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Item')->findOneBySlug($slug);
        if (!$item) 
        {
            throw $this->createNotFoundException();        
        }
        $related=$this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Item')->findAllRelated($item->getId());
        return array('item'=>$item,'related'=>$related,
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
