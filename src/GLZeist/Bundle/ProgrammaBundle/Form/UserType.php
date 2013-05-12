<?php

namespace GLZeist\Bundle\ProgrammaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email','email')
            ->add('role','choice',array('choices' => array('ROLE_USER' => 'Gebruiker','ROLE_MODERATOR'=> 'Redacteur','ROLE_ADMIN'=>'Beheerder') ))
            ->add('password','repeated',array(
                'required'=>false,
                'invalid_message' => 'De wachtwoorden komen niet overeen',
                'first_options' =>array('label' => 'Wachtwoord'),
                'second_options' =>array('label' => 'Herhaal wachtwoord'),
                'type' => 'password'
            ))
                
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GLZeist\Bundle\ProgrammaBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'glzeist_bundle_programmabundle_thematype';
    }
}
