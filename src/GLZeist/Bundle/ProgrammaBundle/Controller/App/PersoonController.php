<?php

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
