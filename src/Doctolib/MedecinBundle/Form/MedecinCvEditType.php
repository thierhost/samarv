<?php

namespace Doctolib\MedecinBundle\Form;

use Doctolib\AdminBundle\Form\SpecialiteType;
use Doctolib\UserBundle\Form\UserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedecinCvEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('user')
                ->remove('specialite')
                ->add('cv');
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