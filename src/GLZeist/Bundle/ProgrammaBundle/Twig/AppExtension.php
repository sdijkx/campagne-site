<?php
namespace GLZeist\Bundle\ProgrammaBundle\Twig;

class AppExtension extends \Twig_Extension
{
    private $em;
    
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em=$em;
    }
    public function getName()
    {
        return 'glzeist';
    }
    
    public function getFunctions()
    {
        return array(
            'themas' => new \Twig_Function_Method($this,'getThemas')
        );
    }
    
    public function getThemas()
    {
        return $this->em->getRepository('GLZeistProgrammaBundle:Thema')->findAll();
    }

}