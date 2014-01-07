<?php

namespace Application\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Application\UserBundle\Entity\Group;

/**
 * Class GroupRepository
 *
 * @package Application\UserBundle\Repository
 */
class GroupRepository extends EntityRepository
{
    public function findAllWithCount()
    {
        return $this->createQueryBuilder('g')
            ->join('g.users', 'u')
            ->addSelect('u')
            ->getQuery()
            ->getResult();
    }
}
