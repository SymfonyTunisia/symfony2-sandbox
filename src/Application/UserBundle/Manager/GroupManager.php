<?php

namespace Application\UserBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Doctrine\GroupManager as BaseGroupManager;
use FOS\UserBundle\Model\GroupInterface;

class GroupManager extends BaseGroupManager
{
    protected $userManager;

    public function __construct(ObjectManager $om, $class, UserManager $userManager)
    {
        parent::__construct($om, $class);
        $this->userManager = $userManager;
    }

    public function deleteGroupAndMoveUsers(GroupInterface $group, GroupInterface $moveTo)
    {
        $this->userManager->moveUsers($group, $moveTo);
        parent::deleteGroup($group);
    }
}
