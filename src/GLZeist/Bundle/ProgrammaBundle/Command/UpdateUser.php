<?php
namespace GLZeist\Bundle\ProgrammaBundle\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateUser extends Command
{
    protected function configure()
    {
        $this
            ->setName('glzeist:user:password')
            ->setDescription('Update password')
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'Username'
            )
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                'Passsword'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container= $this->getApplication()->getKernel()->getContainer();
        
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        
        
        $em=$container->get('doctrine.orm.entity_manager');
        
        $repos=$em->getRepository('GLZeistProgrammaBundle:User');

        $user=$repos->findOneByUsername($username);
        
        if(is_null($user))
        {
            throw new \Exception('De gebruiker komt niet voor in de database');
        }
        
        
        $encoderFactory=$container->get('security.encoder_factory');
        $encodedPassword=$encoderFactory->getEncoder($user)->encodePassword($password,$user->getSalt());
        $user->setPassword($encodedPassword);
        $em->persist($user);
        $em->flush();
        
        $output->writeln('Wachtwoord gewijzigd');
    }
    
}
