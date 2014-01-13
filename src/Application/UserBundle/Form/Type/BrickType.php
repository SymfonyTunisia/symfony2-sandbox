<?php

namespace Application\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use Application\UserBundle\Form\DataTransformer\TagsToIdsTransformer;

class ApplicationType extends AbstractType
{
    private $em;
    
    public function __construct($em) {
        $this->em = $em;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $Application = $builder->getData();
        
        $builder
            ->add('title', 'text')
            ->add('description', 'textarea')
            ->add('canonical_url', 'text')
            ->add('content', 'textarea')
            ->add('application_license', 'entity', array(
                'class' => 'ApplicationSiteBundle:ApplicationLicense',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.title', 'ASC');
                },
                'property' => 'title',
                'empty_value' => '== no license =='
            ))
        ;
        
        // data transformer for ApplicationHasTags field
        $transformer = new TagsToIdsTransformer($this->em, $Application);
        
        // add ApplicationHasTags field, with Data Transformer
        $builder->add(
            $builder->create('ApplicationHasTags', 'hidden')->addModelTransformer($transformer)
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\SiteBundle\Entity\Application'
        ));
    }

    public function getName()
    {
        return 'application_userbundle_Applicationtype';
    }
}