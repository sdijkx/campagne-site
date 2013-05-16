<?php

namespace GLZeist\Bundle\ProgrammaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ThemaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titel')
            ->add('metaDescription','textarea')
            ->add('tekst','textarea',array('required'=>false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GLZeist\Bundle\ProgrammaBundle\Entity\Thema'
        ));
    }

    public function getName()
    {
        return 'glzeist_bundle_programmabundle_thematype';
    }
}
