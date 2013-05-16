<?php

namespace Entities;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 *
 * @property-read int $id
 * @property-read string $username
 * @property string $email
 * @property string $password
 * @property string $role
 */
class User extends BaseEntity
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 * @var int
	 */
	private $id;
	/**
	 * @ORM\Column(unique=true)
	 * @var string
	 */
	private $username;
	/**
	 * @ORM\Column
	 * @var string
	 */
	private $email;
	/**
	 * @ORM\Column
	 * @var string
	 */
	protected $password;

	/**
	 * @param string
	 * @return User
	 */
	public function __construct($username = NULL)
	{
		$this->username = static::normalizeString($username);
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	public function setUsername($username) {
		$this->username = $username;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param string
	 * @return User
	 */
	public function setPassword($password)
	{
		$this->password = static::normalizeString($password);
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param string
	 * @return User
	 */
	public function setEmail($email)
	{
		$this->email = static::normalizeString($email);
		return $this;
	}

	/**
	 * @param string
	 * @return string
	 */
	protected static function normalizeString($s)
	{
		$s = trim($s);
		return $s === "" ? NULL : $s;
	}
}
