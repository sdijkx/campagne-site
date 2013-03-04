<?php

namespace GLZeist\Bundle\ProgrammaBundle\Controller\App;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ItemController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $items = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Item')->findAllForHomePage();
        return array('items' => $items);
    }
    
    /**
     * @Route("/search")
     * @Template()
     */
    public function searchAction()
    {
        $search=$this->getRequest()->get('search');
        $results = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Item')->findAllForSearch($search);        
        return array('results'=>$results,'search'=>$search);
    }
    
    
    /**
     * @Route("/item/{slug}")
     * @Template()
     */
    public function detailAction($slug)
    {
        $item = $this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Item')->findOneBySlug($slug);
        if (!$item) 
        {
            throw $this->createNotFoundException();        
        }
        $related=$this->getDoctrine()->getRepository('GLZeistProgrammaBundle:Item')->findAllRelated($item->getId());
        return array('item'=>$item,'related'=>$related);
    }
    
}
