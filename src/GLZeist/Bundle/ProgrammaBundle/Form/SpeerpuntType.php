<?php

namespace GLZeist\Bundle\ProgrammaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SpeerpuntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titel')
            ->add('content')
            ->add('url')
            ->add('afbeelding')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GLZeist\Bundle\ProgrammaBundle\Entity\Speerpunt'
        ));
    }

    public function getName()
    {
        return 'glzeist_bundle_programmabundle_speerpunttype';
    }
}
