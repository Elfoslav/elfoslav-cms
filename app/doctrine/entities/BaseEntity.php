<?php

namespace Entities;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @author Tomáš Hromník <tom.hromnik@gmail.com>
 *
 * Base entity class for all entities
 */
abstract class BaseEntity extends \Nette\Object
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 * @var int
	 */
	protected $id;

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
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

	/**
	 * Load entity from Array
	 */
	public function fromArray(Array $arr) {
		foreach($arr as $property => $value) {
			$setter = 'set' . ucfirst($property);
			if(method_exists($this, $setter)) {
				$this->$setter($value);
			} else {
				throw new \Nette\MemberAccessException('Method "' . $setter . '" does not exist in class "' . get_class($this) .'"');
			}
		}
	}

	/**
	 * Convert entity to Array
	 */
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
