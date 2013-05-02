<?php

namespace Entities;

use \Doctrine\ORM\Mapping as ORM,
	\Nette\Utils\Strings;

/**
 * @ORM\Entity(repositoryClass="Repositories\ArticleRepository")
 * @ORM\Table(name="Article")
 * @ORM\HasLifecycleCallbacks
 */
class Article extends BaseEntity
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 * @var int
	 */
	private $id;
	/**
	 * @ORM\Column(type="string", unique=true, length=103)
	 * @var string
	 */
	protected $title;
	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	protected $content;
	/**
	 * @ORM\Column(type="string", unique=true)
	 * @var string
	 */
	protected $slug;

	protected $category;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $created;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $updated;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $published;


	public function __construct()
	{
		$this->setCreated(new \DateTime());
		$this->setUpdated(new \DateTime());
	}

	/**
	 * @ORM\PreUpdate
	 */
	public function setUpdatedValue() {
		$this->setUpdated(new \DateTime());
	}

    function setTitle($value){
        $this->title = $value;
    }

    function getTitle(){
        return $this->title;
    }

    function setContent($value){
        $this->content = $value;
    }

    function getContent(){
        return $this->content;
    }

    function setSlug($value){
        $this->slug = $value;
    }

    function getSlug(){
        return $this->slug;
    }

    function setCategory($value){
        $this->category = $value;
    }

    function getCategory(){
        return $this->category;
    }

    function setCreated($value){
        $this->created = $value;
    }

    function getCreated(){
        return $this->created;
    }

    function setUpdated($value){
        $this->updated = $value;
    }

    function getUpdated(){
        return $this->updated;
    }

    function setPublished($value){
        $this->published = $value;
    }

    function getPublished(){
        return $this->published;
    }

}
