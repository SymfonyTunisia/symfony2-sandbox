<?php

namespace Application\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('recaptcha', 'ewz_recaptcha', array(
            'attr' => array(
                'options' => array(
                    'theme' => 'clean'
                )
            ),
            'mapped' => false,
            'constraints'   => array(
                new Recaptcha\True()
            )
        ));
    }

    public function getName()
    {
        return 'application_user_registration';
    }
}
