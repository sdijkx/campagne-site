<?php
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
