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

namespace GLZeist\Bundle\ProgrammaBundle\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use GLZeist\Bundle\ProgrammaBundle\Entity\User;

class AddUser extends Command
{
    protected function configure()
    {
        $this
            ->setName('glzeist:adduser')
            ->setDescription('Add a user')
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'Username'
            )
            ->addArgument(
                'role',
                InputArgument::REQUIRED,
                'Role'
            )                
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                'Password'
            )
            ->addArgument(
                'email',
                InputArgument::REQUIRED,
                'Email'
            )
                
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container= $this->getApplication()->getKernel()->getContainer();
        
        $username = $input->getArgument('username');
        $role=$input->getArgument('role');
        $password = $input->getArgument('password');
        $email = $input->getArgument('email');
        
        
        $em=$container->get('doctrine.orm.entity_manager');
        
        $repos=$em->getRepository('GLZeistProgrammaBundle:User');
        
        $user=$repos->findOneByUsername($username);
        if(!is_null($user))
        {
            throw new \Exception('De gebruiker komt al voor in de database');
        }
        
        if(!in_array($role,User::$ROLES))
        {
            throw new \Exception('Onbekende gebruikers role ');
        }

        

        $user=new \GLZeist\Bundle\ProgrammaBundle\Entity\User();
        $user->setUsername($username);
        
        $encoderFactory=$container->get('security.encoder_factory');
        $encodedPassword=$encoderFactory->getEncoder($user)->encodePassword($password,$user->getSalt());
        
        $user->setPassword($encodedPassword);
        $user->setRole($role);
        $user->setEmail($email);
        $em->persist($user);
        $em->flush();
        
        $output->writeln('Gebruiker toegevoegd');
    }
    
}
