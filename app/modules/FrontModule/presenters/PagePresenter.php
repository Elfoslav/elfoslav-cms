<?php

namespace FrontModule;

use Nette\Application\BadRequestException;

/**
 * Page presenter.
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavCMS
 */
class PagePresenter extends BasePresenter
{

	public function renderDefault($slug) {

		$page = $this->editablePageRepository->findOneBy(array(
			'slug' => $slug
		));

		if(!$page || (!$page->isPublished() && !$this->user->isLoggedIn())) {
			throw new BadRequestException('Page not found');
		}

		$this->template->page = $page;
	}

}
