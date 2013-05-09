<?php
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
