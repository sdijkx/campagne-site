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

class ListUsers extends Command
{
    protected function configure()
    {
        $this
            ->setName('glzeist:users')
            ->setDescription('List all users')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container= $this->getApplication()->getKernel()->getContainer();
        
        $em=$container->get('doctrine.orm.entity_manager');
        $repos=$em->getRepository('GLZeistProgrammaBundle:User');
        $users=$repos->findAll();
        
        $output->write(sprintf("\t%s\t%s","Username","Role"),true);
        $output->write("--------------------------------------------------------",true);
        foreach($users as $user)
        {
            $output->write(sprintf("\t%s\t%s",$user->getUsername(),$user->getRole()),true);
        }
    }
    
}
