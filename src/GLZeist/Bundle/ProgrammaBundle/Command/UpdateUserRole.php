<?php
namespace GLZeist\Bundle\ProgrammaBundle\Command;
use GLZeist\Bundle\ProgrammaBundle\Entity\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateUserRole extends Command
{
    protected function configure()
    {
        $this
            ->setName('glzeist:role')
            ->setDescription('Update user role')
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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container= $this->getApplication()->getKernel()->getContainer();
        
        $username = $input->getArgument('username');
        $role = $input->getArgument('role');
        
        
        $em=$container->get('doctrine.orm.entity_manager');
        
        $repos=$em->getRepository('GLZeistProgrammaBundle:User');

        $user=$repos->findOneByUsername($username);
        
        if(is_null($user))
        {
            throw new \Exception('De gebruiker komt niet voor in de database');
        }
        
        if(!in_array($role,User::$ROLES))
        {
            throw new \Exception('Onbekende gebruikers role');
        }
        
        $user->setRole($role);
        $em->persist($user);
        $em->flush();
        
        $output->writeln('Rol gewijzigd');
    }
    
}
