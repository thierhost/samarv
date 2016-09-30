<?php

namespace Doctolib\MedecinBundle\Form;

use Doctolib\AdminBundle\Form\SpecialiteType;
use Doctolib\UserBundle\Form\UserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedecinType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', new UserType())
            ->add('specialite', 'entity',array(
                'class' => 'DoctolibAdminBundle:Specialite',
                'property'=>'nom'
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Doctolib\MedecinBundle\Entity\Medecin'
        ));
    }
}
