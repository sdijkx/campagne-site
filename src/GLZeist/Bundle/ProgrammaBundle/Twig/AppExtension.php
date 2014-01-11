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

namespace GLZeist\Bundle\ProgrammaBundle\Twig;
use \Symfony\Component\Security\Core\SecurityContextInterface;

class AppExtension extends \Twig_Extension
{
    private $em;
    private $securityContext;
    
    public function __construct(\Doctrine\ORM\EntityManager $em,  SecurityContextInterface $securityContext,  \GLZeist\Bundle\ProgrammaBundle\Site $site)
    {
        $this->em=$em;
        $this->securityContext=$securityContext;
        $this->site=$site;
    }
    public function getName()
    {
        return 'glzeist';
    }
    
    public function getFunctions()
    {
        return array(
            'themas' => new \Twig_Function_Method($this,'getThemas'),
            'hoofdstukken' => new \Twig_Function_Method($this,'getHoofdstukken'),
            'kandidaten' => new \Twig_Function_Method($this,'getKandidaten'),
            'is_moderator' => new \Twig_Function_Method($this,'isModerator'),
            'is_admin' => new \Twig_Function_Method($this,'isAdmin')
            
        );
    }
    
    public function getGlobals() {
        return array('site' => $this->site);
    }
    
    public function getThemas()
    {
        return $this->em->getRepository('GLZeistProgrammaBundle:Thema')->findAll();
    }

    public function getHoofdstukken()
    {
        return $this->em->getRepository('GLZeistProgrammaBundle:Hoofdstuk')->findAll();
    }

    public function getKandidaten()
    {
        return $this->em->getRepository('GLZeistProgrammaBundle:Kandidaat')->findAll();
    }
    
    
    public function isModerator()
    {
        return $this->securityContext->isGranted('ROLE_MODERATOR');
    }
    
    public function isAdmin()
    {
        return $this->securityContext->isGranted('ROLE_ADMIN');
    }
    
}