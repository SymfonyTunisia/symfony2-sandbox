<?php

namespace Application\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\GroupableInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_group")
 */
class Group extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Application\UserBundle\Entity\User", mappedBy="groups", fetch="EXTRA_LAZY")
     */
    protected $users;

    /**
     * @ORM\Column(type="boolean", name="default_group")
     */
    protected $default;

    /**
     * Constructor
     */
    public function __construct($name, $roles = array())
    {
        parent::__construct($name, $roles);
        $this->users = new ArrayCollection();
        $this->default = false;
    }

    /**
     * @return bool
     */
    function isDefault()
    {
        return $this->default;
    }

    /**
     * @deprecated
     * @param bool $default
     * @return $this
     */
    function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * Add users
     *
     * @param GroupableInterface $user
     * @return Group
     */
    public function addUser(GroupableInterface $user)
    {
        $this->users[] = $user;
        return $this;
    }

    /**
     * Remove users
     *
     * @param GroupableInterface $user
     * @return Group
     */
    public function removeUser(GroupableInterface $user)
    {
        $this->users->removeElement($user);
        return $this;
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
