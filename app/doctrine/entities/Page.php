<?php

namespace Entities;

use \Nette\Utils\Strings;

/**
 * @author Tomáš Hromník <tom.hromnik@gmail.com>
 *
 * Base entity class for all entities
 */
class Page extends BaseEntity
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 * @var int
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", unique=true)
	 * @var string
	 */
	protected $slug;

	public function fromArray(Array $arr) {
		parent::fromArray($arr);

		if(!$this->slug)
			$this->slug = Strings::webalize($this->title);
	}

	public function getId() {
		return $this->id;
	}

	function setSlug($value){
        $this->slug = $value;
    }

    function getSlug(){
        return $this->slug;
    }

}
