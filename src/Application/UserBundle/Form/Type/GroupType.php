<?php

namespace Application\UserBundle\Form\Type;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

class GroupType extends AbstractType
{
    protected $roles;

    protected $translator;

    public function __construct($roles, Translator $translator)
    {
        $this->roles = $roles;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('roles', 'choice', array(
                'expanded' => true,
                'multiple' => true,
                'choices' => $this->getChoices()
            ))
            ->add('submit', 'submit')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\UserBundle\Entity\Group',
        ));
    }

    public function getName()
    {
        return 'application_userbundle_grouptype';
    }

    private function getChoices()
    {
        $hierarchy = new RoleHierarchy($this->roles);
        $higher = new Role('ROLE_SUPER_ADMIN');

        $roles = $hierarchy->getReachableRoles(array($higher));
        $choices = array();

        foreach($roles as $role) {
            $choices[$role->getRole()] = $this->translator->trans($role->getRole());
        }

        return $choices;
    }
}
