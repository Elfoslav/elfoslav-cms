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
		$this->template->article = $article;
	}
}
