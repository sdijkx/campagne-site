<?php

namespace GLZeist\Bundle\ProgrammaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParagraafType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titel')
            ->add('hoofdstuk','entity',array('expanded'=>true,'class'=>'GLZeistProgrammaBundle:Hoofdstuk'))
            ->add('items','collection',array('type'=>'entity', 'allow_add'=>true,'allow_delete'=>true, 'options' => array( 'class'=>'GLZeistProgrammaBundle:Item')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GLZeist\Bundle\ProgrammaBundle\Entity\Paragraaf'
        ));
    }

    public function getName()
    {
        return 'glzeist_bundle_programmabundle_paragraaftype';
    }
}
