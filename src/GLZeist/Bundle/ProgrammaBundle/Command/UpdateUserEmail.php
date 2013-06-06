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
use GLZeist\Bundle\ProgrammaBundle\Entity\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateUserEmail extends Command
{
    protected function configure()
    {
        $this
            ->setName('glzeist:user:email')
            ->setDescription('Update user email')
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'Username'
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
        $email = $input->getArgument('email');
        
        
        $em=$container->get('doctrine.orm.entity_manager');
        
        $repos=$em->getRepository('GLZeistProgrammaBundle:User');

        $user=$repos->findOneByUsername($username);
        
        if(is_null($user))
        {
            throw new \Exception('De gebruiker komt niet voor in de database');
        }
        
        
        $user->setEmail($email);
        $em->persist($user);
        $em->flush();
        
        $output->writeln('Email gewijzigd');
    }
    
}
