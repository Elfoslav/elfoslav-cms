<?php

namespace Entities;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @author Tomáš Hromník <tom.hromnik@gmail.com>
 *
 * Tag for Article
 *
 * @ORM\Entity
 * @ORM\Table(name="tag")
 */
class Tag extends BasePage
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 * @var int
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", unique=true, length=100)
	 * @var string
	 */
	protected $title;

	/**
     * @ORM\ManyToMany(targetEntity="Article")
     * @var array
     **/
	protected $articles;

	public function getId() {
		return $this->id;
	}

	public function setTitle($value) {
		if(!$this->slug) {
			$this->setSlug($value);
		}
		$this->title = $value;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setArticles($value) {
		$this->articles = $value;
	}

	public function getArticles() {
		return $this->articles;
	}

}