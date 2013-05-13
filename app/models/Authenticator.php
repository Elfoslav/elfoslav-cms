<?php

namespace Models;

use \Nette\Security as NS;


/**
 * Users authenticator.
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavCMS
 */
class Authenticator extends \Nette\Object implements NS\IAuthenticator
{
	/** @var \Doctrine\ORM\EntityRepository */
	private $users;

	public function __construct(\Doctrine\ORM\EntityRepository $users)
	{
		$this->users = $users;
	}

	/**
	 * Performs an authentication
	 * @param  array
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;
		$user = $this->users->findOneBy(array('username' => $username));

		if (!$user) {
			throw new NS\AuthenticationException("User '$username' not found.", self::IDENTITY_NOT_FOUND);
		}

		if ($user->getPassword() !== $this->calculateHash($password, $user->getPassword())) {
			throw new NS\AuthenticationException("Invalid password. " . $user->getPassword(), self::INVALID_CREDENTIAL);
		}

		return new NS\Identity($user->getId(), $user->getRole(), array(
			'username' => $user->getUsername(),
			'email' => $user->getEmail(),
		));
	}



	/**
	 * Computes salted password hash.
	 * @param  string
	 * @return string
	 */
	public function calculateHash($password, $salt = null)
	{
		if ($salt === null) {
			$salt = '$2a$07$' . \Nette\Utils\Strings::random(22);
		}
		return crypt($password, $salt);
	}

}
