<?php
namespace GLZeist\Bundle\ProgrammaBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadTrefwoordData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    
    private $container;
    
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container=$container;
    }
    
    public function getOrder()
    {
        return 2;
    }

    public function load(ObjectManager $manager)
    {
        
        $trefwoorden=array(
            'Austerlitz',
            'Den Dolder',
            'Bosch en Duin',
            'Huis ter Heide',
            'Zeist West',
            'Vollenhoven',
            'Brugakker',
            'Couwenhoven',
            'Wilhelminapark',
            'Lyceumkwartier',            
            'Zonnepanelen',
            'Duurzaamheid',
            'Sociaal',
            'Groen',
            'WMO',
            'AWBZ',
            'Ouderen',
            'Jongeren',
            'Scholieren',
            'Werk',
            'Inkomen',
            'Regionaal',
            'Afval',
            'Gemeentebelasting'
        );
        
        foreach($trefwoorden as $tekst)
        {
            $trefwoord=new \GLZeist\Bundle\ProgrammaBundle\Entity\Trefwoord();
            $trefwoord->setTrefwoord($tekst);
            $manager->persist($trefwoord);
        }
        
        
        
        $manager->flush();
    }
    
    private function encodePassword(\Symfony\Component\Security\Core\User\UserInterface $user,$password)
    {
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        return $encoder->encodePassword($password,$user->getSalt());
    }

}