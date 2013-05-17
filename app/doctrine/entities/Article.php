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
     * @ORM\ManyToMany(targetEntity="ArticleCategory", inversedBy="articles")
     **/
	protected $categories;

	/**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="articles")
     **/
	protected $tags;


	public function __construct()
	{
		$this->setCreated(new \DateTime());
		$this->setUpdated(new \DateTime());
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

}
