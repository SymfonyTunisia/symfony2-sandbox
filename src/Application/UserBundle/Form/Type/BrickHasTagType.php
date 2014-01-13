<?php
namespace Application\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ApplicationHasTagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tag', 'entity', array(
            'class' => 'ApplicationSiteBundle:Tag',
            'property' => 'title'
        ));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Application\SiteBundle\Entity\ApplicationHasTag',
        );
    }

    public function getName()
    {
        return 'application_userbundle_application_has_tagtype';
    }
}