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