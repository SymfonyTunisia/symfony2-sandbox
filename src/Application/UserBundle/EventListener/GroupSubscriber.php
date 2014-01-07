<?php

namespace Application\UserBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Application\UserBundle\Entity\Group;

class GroupSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
        );
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Group) {
            $entity->addRole('ROLE_GROUP_'.$entity->getId());
        }

        $args->getEntityManager()->flush($entity);
    }
}
