<?php
namespace GLZeist\Bundle\ProgrammaBundle\Twig;
use \Symfony\Component\Security\Core\SecurityContextInterface;

class AppExtension extends \Twig_Extension
{
    private $em;
    private $securityContext;
    
    public function __construct(\Doctrine\ORM\EntityManager $em,  SecurityContextInterface $securityContext)
    {
        $this->em=$em;
        $this->securityContext=$securityContext;
    }
    public function getName()
    {
        return 'glzeist';
    }
    
    public function getFunctions()
    {
        return array(
            'themas' => new \Twig_Function_Method($this,'getThemas'),
            'is_moderator' => new \Twig_Function_Method($this,'isModerator'),
            'is_admin' => new \Twig_Function_Method($this,'isAdmin')
            
        );
    }
    
    public function getThemas()
    {
        return $this->em->getRepository('GLZeistProgrammaBundle:Thema')->findAll();
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