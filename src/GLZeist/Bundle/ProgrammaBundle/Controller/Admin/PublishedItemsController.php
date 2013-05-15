<?php

namespace GLZeist\Bundle\ProgrammaBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use GLZeist\Bundle\ProgrammaBundle\Annotation\Granted;

/**
 * Persoon controller.
 * @Granted(role="ROLE_MODERATOR")
 * @Route("/gepubliceerd")
 */

class PublishedItemsController extends Controller {
    
    public function indexAction()
    {
        
    }
    
    public function deleteAction()
    {
        
    }
    
}
