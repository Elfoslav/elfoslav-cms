<?php

namespace Entities;

use \Doctrine\ORM\Mapping as ORM,
	\Nette\Utils\Strings;

/**
 * @author Tomáš Hromník <tom.hromnik@gmail.com>
 *
 * Base Page
 *
 * @ORM\MappedSuperclass
 */
abstract class BasePage extends BaseEntity
{
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

	function setSlug($value){
        $this->slug = Strings::webalize($value);
    }

    function getSlug(){
        return $this->slug;
    }

}
