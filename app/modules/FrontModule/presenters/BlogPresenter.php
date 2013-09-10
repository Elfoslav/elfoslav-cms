<?php

namespace FrontModule;
/**
 * Blog presenter
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavCMS
 */
class BlogPresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->template->articles = $this->articleRepository->findBy(array(
			'published' => TRUE
		));
	}

	public function renderShow($slug) {
		$article = $this->articleRepository->findOneBy(array('slug' => $slug));
		if(!$article || (!$article->isPublished() && !$this->user->isLoggedIn())) {
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
