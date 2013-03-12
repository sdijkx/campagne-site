<?php
namespace GLZeist\Bundle\ProgrammaBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadThemaData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    
    private $container;
    
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container=$container;
    }
    
    public function getOrder()
    {
        return 1;
    }

    public function load(ObjectManager $manager)
    {
        $thema=new \GLZeist\Bundle\ProgrammaBundle\Entity\Thema();
        $thema->setTitel("Sociaal");
        $manager->persist($thema);
        
        $thema=new \GLZeist\Bundle\ProgrammaBundle\Entity\Thema();
        $thema->setTitel("Zorg");
        $manager->persist($thema);

        $thema=new \GLZeist\Bundle\ProgrammaBundle\Entity\Thema();        
        $thema->setTitel("Onderwijs");
        $manager->persist($thema);
        
        $thema=new \GLZeist\Bundle\ProgrammaBundle\Entity\Thema();        
        $thema->setTitel("Cultuur");
        $manager->persist($thema);
        
        $thema=new \GLZeist\Bundle\ProgrammaBundle\Entity\Thema();        
        $thema->setTitel("Duurzaamheid");
        $manager->persist($thema);
        
        $thema=new \GLZeist\Bundle\ProgrammaBundle\Entity\Thema();
        $thema->setTitel("Ruimte");
        $manager->persist($thema);
        
        $thema=new \GLZeist\Bundle\ProgrammaBundle\Entity\Thema();
        $thema->setTitel("Wijken en kernen");
        $manager->persist($thema);
        
        $thema=new \GLZeist\Bundle\ProgrammaBundle\Entity\Thema();
        $thema->setTitel("Participatie");
        $manager->persist($thema);
        
        $manager->flush();
    }
    
    private function encodePassword(\Symfony\Component\Security\Core\User\UserInterface $user,$password)
    {
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        return $encoder->encodePassword($password,$user->getSalt());
    }

}