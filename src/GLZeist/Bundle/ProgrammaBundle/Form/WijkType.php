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

class WijkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('wijk')
        ->add('metaDescription')
        ->add('samenvatting')
        ->add('tekst')
        ->add('promo') 
            ->add('trefwoorden','entity',array('expanded'=>true,'multiple'=>true,'class' => 'GLZeistProgrammaBundle:Trefwoord',
                'query_builder'=> function($repos) {
                            return $repos->createQueryBuilder('t')->orderBy('t.trefwoord','ASC'); 
                            
                            }))
           ->add('links','collection',array('type'=> new LinkType(),
                'prototype'=>true,
                'allow_add'=>true,
                'allow_delete'=>true,
                'by_reference'=>false))
            ->add('afbeeldingen','collection',array('type'=> new AfbeeldingType(),
                'prototype'=>true,
                'allow_add'=>true,
                'allow_delete'=>true,
                'by_reference'=>false));

        

    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GLZeist\Bundle\ProgrammaBundle\Entity\Wijk'
        ));
    }

    public function getName()
    {
        return 'glzeist_bundle_programmabundle_wijktype';
    }        
}
