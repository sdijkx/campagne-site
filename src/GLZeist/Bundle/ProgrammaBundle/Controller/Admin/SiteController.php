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
     * @Route("/site/banner",name="admin_site_banner")
     */    
    public function bannerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $site=$this->get('gl_zeist_programma.site');
        if(!$site->getBanner() instanceof \Symfony\Component\HttpFoundation\File\File)
        {
            return new \Symfony\Component\BrowserKit\Response('No Banner', 404);
        }
        $path=$site->getBanner()->getRealPath();
        $guesser=  \Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser::getInstance();
        $mimeType=$guesser->guess($path);
        ob_clean();
        header('Content-type: '.$mimeType);
        readfile($path);
        exit;
        
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


        $form = $this->createForm(new SiteType());
        $form->bind($request);

        if ($form->isValid()) {
            try
            {
                $data=$form->getData();
                $banner=$data['banner'];
                if($banner instanceof \Symfony\Component\HttpFoundation\File\UploadedFile)
                {
                    if($banner->getError()!=UPLOAD_ERR_OK)
                    {
                        throw new \Exception($this->codeToMessage($banner->getError()));
                    }               
                }
                $site->update($data);
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
    
    private function codeToMessage($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;

            default:
                $message = "Unknown upload error";
                break;
        }
        return $message;
    }
    
    /**
    * @Route("/site/banner/remove",name="admin_site_remove_banner")
    * @Method("POST")
    */
    
    public function removeBannerAction()
    {
        $site=$this->get('gl_zeist_programma.site');       
        $site->setBanner(null);
        $site->update();
        return $this->redirect($this->generateUrl('admin_site_edit'));
    }
    
}