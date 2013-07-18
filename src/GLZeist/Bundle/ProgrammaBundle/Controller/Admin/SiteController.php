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
use GLZeist\Bundle\ProgrammaBundle\Annotation\Granted;
use GLZeist\Bundle\ProgrammaBundle\Form\SiteType;
use GLZeist\Bundle\ProgrammaBundle\Entity\User;
/**
 * @Granted(role="ROLE_ADMIN")
 */
class SiteController extends Controller
{
    /**
     * @Route("/site/edit",name="admin_site_edit")
     * @Template
     */    
    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $site=$this->get('gl_zeist_programma.site');


        if (!$site) {
            throw $this->createNotFoundException('Unable to load site.');
        }

        $form = $this->createForm(new SiteType(), $site);

        return array(
            'site'      => $site,
            'form'   => $form->createView()
        );                
    }

    /**
     * @Route("/site/update",name="admin_site_update")
     * @Method("POST")
     * @Template("GLZeistProgrammaBundle:Admin/Site:edit.html.twig")
     */    
    
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $site=$this->get('gl_zeist_programma.site');


        $form = $this->createForm(new SiteType(), $site);
        $form->bind($request);

        if ($form->isValid()) {
            try
            {
                $site->update();
                $this->get('session')->getFlashBag()->add('notice','De instellingen zijn bijgewerkt');
                return $this->redirect($this->generateUrl('admin_site_edit'));
            }
            catch(\Exception $e)
            {
                $this->get('session')->getFlashBag()->add('error','De instellingen kunnen niet worden bijgewerkt: '.$e->getMessage());                
            }
        }
        else
        {
                $this->get('session')->getFlashBag()->add('error','Het formulier bevat fouten');                
        }

        return array(
            'site'      => $site,
            'form'   => $form->createView()
        );        
    }
    
}