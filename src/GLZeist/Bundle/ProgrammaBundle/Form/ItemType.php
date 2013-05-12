<?php

namespace GLZeist\Bundle\ProgrammaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titel')
            ->add('kernboodschap', 'textarea',array('required'=>false,'attr'=>array('class'=>'medium')))
            ->add('tweet','textarea',array('required'=>false,'attr'=>array('class'=>'medium')))
            ->add('voorstellen','textarea',array('required'=>false,'attr'=>array('class'=>'medium')))
            ->add('verantwoording','textarea',array('required'=>false,'attr'=>array('class'=>'medium')))
            ->add('hoofdtekst','textarea',array('required'=>false,'attr'=>array('class'=>'large')))
            ->add('file')
            ->add('video')
            ->add('relaties','entity',array('expanded'=>true,'multiple'=>true,'class' => 'GLZeistProgrammaBundle:Item', 
                    'query_builder'=> function($repos) {
                        
                        return $repos->createQueryBuilder('i')->orderBy('i.titel','ASC');
                        
                        }))
            ->add('trefwoorden','entity',array('expanded'=>true,'multiple'=>true,'class' => 'GLZeistProgrammaBundle:Trefwoord',
                'query_builder'=> function($repos) {
                            return $repos->createQueryBuilder('t')->orderBy('t.trefwoord','ASC'); 
                            
                            }))
            ->add('thema','entity',array('expanded'=>true,'class' => 'GLZeistProgrammaBundle:Thema'))
            ->add('links','collection',array('type'=> new LinkType(),
                'prototype'=>true,
                'allow_add'=>true,
                'allow_delete'=>true,
                'by_reference'=>false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GLZeist\Bundle\ProgrammaBundle\Entity\Item'
        ));
    }

    public function getName()
    {
        return 'glzeist_bundle_programmabundle_itemtype';
    }
}
