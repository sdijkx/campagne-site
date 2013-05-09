<?php
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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container= $this->getApplication()->getKernel()->getContainer();
        
        $username = $input->getArgument('username');
        $role=$input->getArgument('role');
        $password = $input->getArgument('password');
        
        
        $em=$container->get('doctrine.orm.entity_manager');
        
        $repos=$em->getRepository('GLZeistProgrammaBundle:User');
        
        $user=$repos->findOneByUsername($username);
        if(!is_null($user))
        {
            throw new \Exception('De gebruiker komt al voor in de database');
        }
        
        if(!in_array($role,User::$ROLES))
        {
            throw new \Exception('Onbekende gebruikers role');
        }

        

        $user=new \GLZeist\Bundle\ProgrammaBundle\Entity\User();
        $user->setUsername($username);
        
        $encoderFactory=$container->get('security.encoder_factory');
        $encodedPassword=$encoderFactory->getEncoder($user)->encodePassword($password,$user->getSalt());
        
        $user->setPassword($encodedPassword);
        $user->setRole($role);
        $em->persist($user);
        $em->flush();
        
        $output->writeln('Gebruiker toegevoegd');
    }
    
}
