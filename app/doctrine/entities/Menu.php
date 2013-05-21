<?php

namespace Entities;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Repositories\MenuRepository")
 * @ORM\Table(name="menu")
 */
class Menu extends BaseEntity
{

	/**
	 * @ORM\Column(type="string")
	 */
	protected $title;

	/**
     * @ORM\OneToOne(targetEntity="BasePage", inversedBy="menu")
     **/
	protected $page;

	public function getPage() {
		return $this->page;
	}

	public function setPage($page) {
		$this->page = $page;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

}
