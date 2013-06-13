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
     * @var array
     **/
	protected $categories = array();

	/**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="articles")
     * @var array
     **/
	protected $tags = array();


	public function __construct()
	{
		$this->setCreated(new \DateTime());
		$this->setUpdated(new \DateTime());
	}

	/**
	 * @param Entities\ArticleCategory
	 */
	public function addCategory($category){
		$this->categories[] = $category;
	}

	/**
	 * Remove category
	 * @param Entities\ArticleCategory
	 * @return boolean TRUE if category has been removed, FALSE otherwise
	 */
	public function removeCategory($category) {
		foreach($this->categories as $key => $cat) {
			if($cat->getId() == $category->getId()) {
				unset($this->categories[$key]);
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * @param Entities\ArticleCategory
	 * @return TRUE if article has given category, FALSE otherwise
	 */
	public function hasCategory($category) {
		foreach($this->categories as $cat) {
			if($cat->getId() == $category->getId()) {
				return TRUE;
			}
		}
		return FALSE;
	}

    public function setCategories($value){
		if(!is_array($value)) {
			throw new \Nette\InvalidArgumentException('Categories must be type of array');
		}
        $this->categories = $value;
    }

    public function getCategories(){
        return $this->categories;
    }

	public function addTag($tag) {
		$this->tags[] = $tag;
	}

	public function setTags($value) {
		if(!is_array($value)) {
			throw new \Nette\InvalidArgumentException('Tags must be type of array');
		}
		$this->tags = $value;
	}

	public function getTags() {
		return $this->tags;
	}

}
