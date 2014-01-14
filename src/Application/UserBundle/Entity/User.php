<?php

namespace Application\UserBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\GroupInterface;
use FOS\UserBundle\Model\User as BaseUser;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\MessageBundle\Model\ParticipantInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser implements ParticipantInterface {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToMany(targetEntity="Application\UserBundle\Entity\Group", inversedBy="users")
	 */
	protected $groups;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime", name="registration_date")
	 * @Gedmo\Timestampable(on="create")
	 */
	protected $registrationDate;

	/**
	 * @var string
	 * @ORM\Column(type="string", name="firstname", nullable=true)
	 * @Assert\NotBlank(groups={"identity"})
	 */
	protected $firstname;

	/**
	 * @var string
	 * @ORM\Column(type="string", name="lastname", nullable=true)
	 * @Assert\NotBlank(groups={"identity"})
	 */
	protected $lastname;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime", name="birthdate", nullable=true)
	 * @Assert\DateTime(groups={"identity"})
	 */
	protected $birthdate;

	/**
	 * @var boolean $emailpolicy_send_on_new_message
	 *
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $emailpolicy_send_on_new_message = true;
	
	/**
	 * @var datetime $created_at
	 *
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime")
	 */
	protected $created_at;

	/**
	 * @var datetime $updated_at
	 *
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(type="datetime")
	 */
	protected $updated_at;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->groups = new ArrayCollection();
	}

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set registrationDate
	 *
	 * @param \DateTime $registrationDate
	 * @return User
	 */
	public function setRegistrationDate(\DateTime $registrationDate) {
		$this->registrationDate = $registrationDate;
		return $this;
	}

	/**
	 * Get registrationDate
	 *
	 * @return \DateTime
	 */
	public function getRegistrationDate() {
		return $this->registrationDate;
	}

	/**
	 * Add groups
	 *
	 * @param GroupInterface $groups
	 * @return User
	 */
	public function addGroup(GroupInterface $groups) {
		$this->groups[] = $groups;

		return $this;
	}

	/**
	 * Remove groups
	 *
	 * @param GroupInterface $groups
	 * @return User
	 */
	public function removeGroup(GroupInterface $groups) {
		$this->groups->removeElement($groups);
		return $this;
	}

	/**
	 * Get groups
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getGroups() {
		return $this->groups;
	}

	/**
	 * Set firstname
	 *
	 * @param string $firstname
	 * @return User
	 */
	public function setFirstname($firstname) {
		$this->firstname = $firstname;

		return $this;
	}

	/**
	 * Get firstname
	 *
	 * @return string 
	 */
	public function getFirstname() {
		return $this->firstname;
	}

	/**
	 * Set lastname
	 *
	 * @param string $lastname
	 * @return User
	 */
	public function setLastname($lastname) {
		$this->lastname = $lastname;

		return $this;
	}

	/**
	 * Get lastname
	 *
	 * @return string 
	 */
	public function getLastname() {
		return $this->lastname;
	}

	/**
	 * Set birthdate
	 *
	 * @param \DateTime $birthdate
	 * @return User
	 */
	public function setBirthdate($birthdate) {
		$this->birthdate = $birthdate;

		return $this;
	}

	/**
	 * Get birthdate
	 *
	 * @return \DateTime 
	 */
	public function getBirthdate() {
		return $this->birthdate;
	}

	/**
	 * Set emailpolicy_send_on_new_message
	 *
	 * @param boolean $emailpolicySendOnNewMessage
	 * @return User
	 */
	public function setEmailpolicySendOnNewMessage($emailpolicySendOnNewMessage)
	{
		$this->emailpolicy_send_on_new_message = $emailpolicySendOnNewMessage;
	
		return $this;
	}
	
	/**
	 * Get emailpolicy_send_on_new_message
	 *
	 * @return boolean
	 */
	public function getEmailpolicySendOnNewMessage()
	{
		return $this->emailpolicy_send_on_new_message;
	}
	
	/**
	 * Set created_at
	 *
	 * @param \DateTime $createdAt
	 * @return User
	 */
	public function setCreatedAt($createdAt) {
		$this->created_at = $createdAt;

		return $this;
	}

	/**
	 * Get created_at
	 *
	 * @return \DateTime 
	 */
	public function getCreatedAt() {
		return $this->created_at;
	}

	/**
	 * Set updated_at
	 *
	 * @param \DateTime $updatedAt
	 * @return User
	 */
	public function setUpdatedAt($updatedAt) {
		$this->updated_at = $updatedAt;

		return $this;
	}

	/**
	 * Get updated_at
	 *
	 * @return \DateTime 
	 */
	public function getUpdatedAt() {
		return $this->updated_at;
	}
	
	public function hasRole($role) {
		/** @var Group $group */
		foreach ($this->groups as $group) {
			foreach ($group->getRoles() as $groupRole) {
				if ($role === $groupRole) {
					return true;
				}
			}
		}
	
		return parent::hasRole($role);
	}

}