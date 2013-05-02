<?php

namespace Entities;

use \Nette\Utils\Strings;

/**
 * @author Tomáš Hromník <tom.hromnik@gmail.com>
 *
 * Base entity class for all entities
 */
abstract class BaseEntity extends \Nette\Object
{
	/**
	 * @param string
	 * @return string
	 */
	protected static function normalizeString($s)
	{
		$s = trim($s);
		return $s === "" ? NULL : $s;
	}

	public function fromArray(Array $arr) {
		foreach($arr as $property => $value) {
			$this->$property = $value;
		}

		if(!$this->slug)
			$this->slug = Strings::webalize($this->title);
	}

	public function __call($name, $arguments) {
		$get = $set = FALSE;

		if (preg_match('/^get/', $name)) {
			$name = preg_replace('/^get(.*)/', '\1', $name);
			$get = TRUE;
		} elseif (preg_match('/^set/', $name)) {
			$name = preg_replace('/^set(.*)/', '\1', $name);
			$set = TRUE;
		} else {
			throw new \BadMethodCallException('Calling an undefined method "' . $name . '".');
		}

		$name[0] = strtolower($name[0]);
		preg_replace_callback('/[A-Z]/', function($c) {
			return '_' . strtolower($c[0]);
		}, $name);

		//$reflection = $this->getReflection();
		//if (!$reflection->hasProperty($name)) {
		//
		//	if ($get AND Strings::endsWith(strtolower($name), self::IDENTIFIER_KEY)
		//		AND $reflection->hasProperty($association = substr($name, 0, strlen($name) - 2))
		//		AND ($this->$association instanceof Entity OR $reflection->implementsInterface('Doctrine\ORM\Proxy\Proxy'))) {
		//		$em = $arguments[0];
		//		$indentifier = $em->getUnitOfWork()->getEntityIdentifier($this->$association);
		//		return $indentifier[self::IDENTIFIER_KEY];
		//	}
		//	throw new \BadMethodCallException('Calling a undefined method "' . $name . '".');
		//}

		if ($get)
			return $this->$name;
		else
			$this->$name = $arguments[0];
	}
}
