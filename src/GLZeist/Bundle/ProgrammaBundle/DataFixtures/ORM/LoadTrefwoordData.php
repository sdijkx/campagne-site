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