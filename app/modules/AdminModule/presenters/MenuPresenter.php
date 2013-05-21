<?php

namespace AdminModule;

use Nette\Application\UI\Form;

/**
 * Menu presenter.
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavCMS
 */
class MenuPresenter extends SecuredPresenter
{

	public function renderDefault() {
		$this->template->menus = $this->menuRepository->findAll();
	}

	public function actionCreate() {

	}

	protected function createComponentMenuForm() {
		$form = new Form;
		$form->getElementPrototype()->class = 'menu-form';
		$form->addText('title', 'Názov menu')
			->setAttribute('class', 'menu-title');

		$pages = $this->basePageRepository->findBy(array('published' => TRUE));
		$pagesArray = array();
		foreach($pages as $page) {
			$pagesArray[$page->getId()] = $page->getTitle();
		}
		$form->addSelect('page', 'Stránka v menu', $pagesArray)
			->setAttribute('class', 'page-select');
		$form->addSubmit('submit', 'Uložiť');

		return $form;
	}

	public function createComponentCreateMenuForm() {
		$form = $this->createComponentMenuForm();
		$form->onSuccess[] = callback($this, 'createMenuSubmitted');

		return $form;
	}

	public function createMenuSubmitted($form) {
		$values = $form->getValues(TRUE);
		$pageId = $values['page'];

		$page = $this->basePageRepository->find($pageId);

		$newMenu = new \Entities\Menu;
		$newMenu->setTitle($values['title']);
		$newMenu->setPage($page);

		$this->em->persist($newMenu);
		try {
			$this->em->flush();
		} catch (\Exception $e) {
			//TODO Log exception
			throw $e;
		}

		$this->flashMessage('Menu vytvorené');

		$this->redirect('default');
	}

	public function createComponentUpdateMenuFofm() {
		$form = $this->createComponentMenuForm();
		$form->addHidden('id');
		$form->onSuccess[] = callback($this, 'updateMenuSubmitted');

		return $form;
	}

	public function updateMenuSubmitted($form) {
		$values = $form->getValues(TRUE);
		dump($values);
	}

	/** @var \Repositories\menuRepository */
	protected $menuRepository;

	/**
	 * @param \Repositories\MenuRepository
	 */
	public function injectMenuRepository(\Repositories\MenuRepository $menuRepository)
    {
		if ($this->menuRepository) {
            throw new Nette\InvalidStateException('MenuRepository has already been set');
        }
        $this->menuRepository = $menuRepository;
    }
}
