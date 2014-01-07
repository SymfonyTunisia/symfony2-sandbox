<?php

namespace Application\UserBundle\Form\Type;

use Application\NewsBundle\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditUserType extends BaseUserType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'fos_user.password.mismatch',
            'required' => false
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\UserBundle\Entity\User',
            'validation_groups' => array('Profile', 'Default'),
        ));
    }

    public function getName()
    {
        return 'application_userbundle_editusertype';
    }
}
