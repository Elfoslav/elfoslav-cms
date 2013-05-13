<?php

namespace Entities;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Repositories\ArticleRepository")
 * @ORM\Table(name="articles")
 * @ORM\HasLifecycleCallbacks
 */
class Article extends BasePage
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 * @var int
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", unique=true, length=150)
	 * @var string
	 */
	protected $title;

	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	protected $content;

	/**
     * @ORM\ManyToMany(targetEntity="ArticleCategory", inversedBy="articles")
     **/
	protected $categories;

	/**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="articles")
     **/
	protected $tags;

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

	public function fromArray(Array $arr) {
		parent::fromArray($arr);
		$this->setUpdatedValue();
	}

	/**
	 * @ORM\PreUpdate
	 */
	public function setUpdatedValue() {
		$this->setUpdated(new \DateTime());
	}

	public function getId() {
		return $this->id;
	}

    public function setTitle($value){
		if(!$this->slug) {
			$this->setSlug($value);
		}
        $this->title = $value;
    }

    public function getTitle(){
        return $this->title;
    }

    public function setContent($value){
        $this->content = $value;
    }

    public function getContent(){
        return $this->content;
    }

	public function addCategory($category ){
		$this->categories[] = $category;
	}

    public function setCategories($value){
        $this->categories = $value;
    }

    public function getCategories(){
        return $this->categories;
    }

	public function addTag($tag) {
		$this->tags[] = $tag;
	}

	public function setTags($value) {
		$this->tags = $value;
	}

	public function getTags() {
		return $this->tags;
	}

    public function setCreated($value){
        $this->created = $value;
    }

    public function getCreated(){
        return $this->created;
    }

    public function setUpdated($value){
        $this->updated = $value;
    }

    public function getUpdated(){
        return $this->updated;
    }

    public function setPublished($value){
        $this->published = $value;
    }

    public function getPublished(){
        return $this->published;
    }

}
