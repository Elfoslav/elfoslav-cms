<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM,
	Nette\Utils\Strings;

/**
 * BasePage
 *
 * @ORM\Entity(repositoryClass="Repositories\BasePageRepository")
 * @ORM\Table(name="pages_all")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({ "pages_all" = "BasePage", "pages_editable" = "EditablePage", "articles" = "Article" })
 */
class BasePage extends SlugPage
{
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

	/**
	 * @ORM\OneToOne(targetEntity="Menu", mappedBy="page")
	 */
	protected $menu;

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

	public function fromArray(Array $arr) {
		parent::fromArray($arr);
		$this->setUpdatedValue();

		if(!$this->slug)
			$this->slug = Strings::webalize($this->title);
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

    public function isPublished(){
        return $this->published;
    }

	public function setMenu($menu) {
		$this->menu = $menu;
	}

	public function getMenu() {
		return $menu;
	}

}
