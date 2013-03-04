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
            ->add('basistekst')
            ->add('hoofdtekst')
            ->add('tweet','textarea')
            ->add('verantwoording')
            ->add('voorstellen')
            ->add('paragraaf','entity',array('expanded'=>true,'class'=>'GLZeistProgrammaBundle:Paragraaf'))
            ->add('trefwoorden','entity',array('expanded'=>true,'multiple'=>true,'class' => 'GLZeistProgrammaBundle:Trefwoord'))
            ->add('media','collection',array('type'=>'entity','options'=> array('class' => 'GLZeistProgrammaBundle:Media')))
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
