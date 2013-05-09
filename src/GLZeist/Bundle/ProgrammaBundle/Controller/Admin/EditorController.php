<?php
namespace GLZeist\Bundle\ProgrammaBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use GLZeist\Bundle\ProgrammaBundle\Entity\Hoofdstuk;
use GLZeist\Bundle\ProgrammaBundle\Form\HoofdstukType;
use GLZeist\Bundle\ProgrammaBundle\Annotation\Granted;

/**
 * Hoofdstuk controller.
 * @Granted(role="ROLE_MODERATOR")
 * @Route("/editor")
 */
class EditorController extends Controller
{

    /**
     * Lists all Hoofdstuk entities.
     *
     * @Route("/index", name="editor")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GLZeistProgrammaBundle:Hoofdstuk')->findAll();
        $items = $em->getRepository('GLZeistProgrammaBundle:Item')->findAll();
        

        return array(
            'hoofdstukken' => $entities,
            'items' => $items
        );
    }    

}