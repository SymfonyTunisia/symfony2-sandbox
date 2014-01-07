<?php

namespace Application\UserBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Application\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Util\ClassUtils;

class UserSubscriber implements EventSubscriber, ContainerAwareInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    private $container;

    public function getSubscribedEvents()
    {
        return array(
            'preUpdate'
        );
    }

    public function preUpdate(PreUpdateEventArgs  $args)
    {
        $em = $args->getEntityManager();
        $entity = $args->getEntity();

        if ($entity instanceof User) {
            // On ne met à jour les Acl que si le field username a changé
            if ($args->hasChangedField('username')) {
                $aclManager = $provider = $this->container->get('application_forum.acl_manager');
                $newUsername = $args->getNewValue('username');

                // Récupération des threads et posts relatifs à l'user courant
                $threads = $em->getRepository('ApplicationForumBundle:Thread')->findBy(array(
                    'user' => $entity->getId(),
                ));
                $posts = $em->getRepository('ApplicationForumBundle:Post')->findBy(array(
                    'user' => $entity->getId(),
                ));

                // Gestion des Acl pour les threads créés par l'user
                foreach ($threads as $thread) {
                    $aclManager->deleteAcl($thread);
                    // modifie dynamiquement l'username du créateur du thread pour updater les acls
                    $thread->getUser()->setUsername($newUsername);
                    // réinsertion des nouvelles acl avec le bon username
                    $aclManager->createAclForThread($thread);
                }

                // Gestion des Acl pour les posts créés par l'user
                foreach ($posts as $post) {
                    $aclManager->deleteAcl($post);
                    // modifie dynamiquement l'username du créateur du post pour updater les acls
                    $post->getUser()->setUsername($newUsername);
                    // réinsertion des nouvelles acl avec le bon username
                    $aclManager->createAclForPost($post);
                }
            }
        }
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
