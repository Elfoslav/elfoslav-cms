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
	}

	public function toArray() {
        $reflection = new \ReflectionClass($this);
        $details = array();
        foreach ($reflection->getProperties(\ReflectionProperty::IS_PROTECTED) as $property) {
            if (!$property->isStatic()) {
                $value = $this->{$property->getName()};

                if ($value instanceof IEntity) {
                    $value = $value->getId();
                } elseif ($value instanceof ArrayCollection || $value instanceof PersistentCollection) {
                    $value = array_map(function (BaseEntity $entity) {
                        return $entity->getId();
                    }, $value->toArray());
                }
                $details[$property->getName()] = $value;
            }
        }
		foreach ($reflection->getProperties(\ReflectionProperty::IS_PRIVATE) as $property) {
            if (!$property->isStatic()) {
                $value = $this->{$property->getName()};

                if ($value instanceof IEntity) {
                    $value = $value->getId();
                } elseif ($value instanceof ArrayCollection || $value instanceof PersistentCollection) {
                    $value = array_map(function (BaseEntity $entity) {
                        return $entity->getId();
                    }, $value->toArray());
                }
                $details[$property->getName()] = $value;
            }
        }
        return $details;
    }

}
