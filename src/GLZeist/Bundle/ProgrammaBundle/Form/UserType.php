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
