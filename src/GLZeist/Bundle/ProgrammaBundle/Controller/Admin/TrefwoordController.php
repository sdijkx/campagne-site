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
use GLZeist\Bundle\ProgrammaBundle\Entity\Trefwoord;
use GLZeist\Bundle\ProgrammaBundle\Form\TrefwoordType;
use GLZeist\Bundle\ProgrammaBundle\Annotation\Granted;

/**
 * Trefwoord controller.
 *
 * @Route("/trefwoord")
 * @Granted(role="ROLE_MODERATOR")
 */
class TrefwoordController extends Controller
{
    /**
     * Lists all Trefwoord entities.
     *
     * @Route("/", name="admin_trefwoord")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->createQuery('SELECT t FROM GLZeistProgrammaBundle:Trefwoord t ORDER BY t.trefwoord ASC')->getResult();

        return array(
            'entities' => $entities,
        );
    }


    /**
     * Displays a form to create a new Trefwoord entity.
     *
     * @Route("/new", name="trefwoord_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Trefwoord();
        $form   = $this->createForm(new TrefwoordType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Trefwoord entity.
     *
     * @Route("/create", name="trefwoord_create")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Trefwoord:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Trefwoord();
        $form = $this->createForm(new TrefwoordType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            try
            {
                $em->persist($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice','Het trefwoord is toegevoegd');
                return $this->redirect($this->generateUrl('admin_trefwoord', array('id' => $entity->getId())));
            }
            catch(\Exception $e)
            {
                $this->get('session')->getFlashBag()->add('error','Het trefwoord kan niet worden toegevoegd');
            }
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }


    /**
     * Deletes a Trefwoord entity.
     *
     * @Route("/{id}/delete", name="trefwoord_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('GLZeistProgrammaBundle:Trefwoord')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Trefwoord entity.');
        }

        try
        {
            $em->remove($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('admin_trefwoord'));
        }
        catch(\Exception $e)
        {
            $this->get('session')->getFlashBag()->add('error','Het trefwoord kan niet worden verwijderd');            
        }
        return $this->redirect($request->headers->get('Referer'));
        
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
