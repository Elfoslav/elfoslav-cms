<?php

namespace Entities;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @author Tomáš Hromník <tom.hromnik@gmail.com>
 *
 * Category for Article
 *
 * @ORM\Entity(repositoryClass="Repositories\ArticleCategoryRepository")
 * @ORM\Table(name="articlecategories")
 */
class ArticleCategory extends SlugPage
{
	/**
	 * @ORM\Column(type="string", unique=true, length=100)
	 * @var string
	 */
	protected $title;

	/**
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="categories")
     * @var array
     **/
	protected $articles;

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

	public function getPublishedArticles() {
		$published = array();
		foreach($this->articles as $article) {
			if($article->isPublished()) {
				$published[] = $article;
			}
		}
		return $published;
	}

}
