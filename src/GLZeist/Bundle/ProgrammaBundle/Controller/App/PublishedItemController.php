<?php

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
