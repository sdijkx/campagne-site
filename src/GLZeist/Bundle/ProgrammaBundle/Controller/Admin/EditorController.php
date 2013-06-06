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