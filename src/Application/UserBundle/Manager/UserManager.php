<?php

namespace Application\UserBundle\Manager;

use FOS\UserBundle\Doctrine\UserManager as BaseUserManager;
use FOS\UserBundle\Model\GroupInterface;
use FOS\UserBundle\Model\GroupableInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserManager extends BaseUserManager
{
    public function __construct(EncoderFactoryInterface $encoderFactory, CanonicalizerInterface $usernameCanonicalizer, CanonicalizerInterface $emailCanonicalizer, ObjectManager $om, $class)
    {
        parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer, $om, $class);
    }

    /**
     * Move users when a group is deleted
     *
     * @param GroupInterface $from
     * @param GroupInterface $to
     */
    public function moveUsers(GroupInterface $from, GroupInterface $to)
    {
        $users = $this->repository->findAllInGroup($from);

        /** @var GroupableInterface $user */
        foreach ($users as $user) {
            $user->removeGroup($from);
            if (!$user->hasGroup($to)) {
                $user->addGroup($to);
            }
            $this->updateUser($user, true);
        }
    }
}
