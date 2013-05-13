<?php

namespace FrontModule;
/**
 * Blog presenter
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavNetteSandbox
 */
class BlogPresenter extends \BaseModule\BasePresenter
{

	public function renderDefault()
	{
		$this->template->articles = $this->articleRepository->findAll();
	}

	public function renderShow($slug) {
		$article = $this->articleRepository->findOneBy(array('slug' => $slug));
		if(!$article) {
			throw new \Nette\Application\BadRequestException('Article does not exist');
		}
		$this->template->article = $article;
	}

	public function renderShowCategory($slug) {
		$category = $this->articleCategoryRepository->findOneBy(array('slug' => $slug));
		if(!$category) {
			throw new \Nette\Application\BadRequestException('ArticleCategory does not exist');
		}
		$this->template->category = $category;
		$this->template->articles = $category->getArticles();
	}

	public function renderShowTag($slug) {
		$tag = $this->tagRepository->findOneBy(array('slug' => $slug));
		if(!$tag) {
			throw new \Nette\Application\BadRequestException('Tag does not exist');
		}
		$this->template->tag = $tag;
		$this->template->articles = $tag->getArticles();
	}
}
