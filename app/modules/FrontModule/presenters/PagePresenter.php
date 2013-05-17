<?php

namespace FrontModule;

use Nette\Application\BadRequestException;

/**
 * Page presenter.
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavCMS
 */
class PagePresenter extends \BaseModule\BasePresenter
{

	public function renderShow($slug) {

		$page = $this->editablePageRepository->finOneBy(array(
			'slug' => $slug
		));

		if(!$page) {
			throw new BadRequestException('Page not found');
		}

		$this->template->page = $page;
	}

}
