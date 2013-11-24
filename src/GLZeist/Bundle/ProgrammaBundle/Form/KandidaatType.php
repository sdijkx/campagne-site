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

class KandidaatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('plek')
            ->add('kern','choice',array(
                'choices'=>array(
                    'Austerlitz' => 'Austerlitz',
                    'Den Dolder' => 'Den Dolder',
                    'Huis ter Heide' => 'Huis ter Heide',
                    'Zeist' => 'Zeist'
                ),
                'required'=>false))
            ->add('akkoord','choice',array(
                'choices' => array(
                    1 => 'Akkoord' , 
                    0 => 'Niet akkoord'
                    ), 
                'empty_value' => 'Geen akkoord ontvangen',
                'required' => false 
            ))
            ->add('kandidaatWilPersonaliaAanleveren','choice',array(
                'label' => 'De kandidaat wil personalia aanleveren',
                'choices' => array(
                    1 => 'Ja' , 
                    0 => 'Nee'
                    ), 
                'empty_value' => 'Onbekend',
                'required' => false 
            ))
            ->add('personalia', 'textarea',array('required'=>false,'attr'=>array('class'=>'large')))
            ->add('file','file',array('required'=>false,'label'=>'Foto'));
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GLZeist\Bundle\ProgrammaBundle\Entity\Kandidaat'
        ));
    }

    public function getName()
    {
        return 'glzeist_bundle_programmabundle_kandidaattype';
    }
}
