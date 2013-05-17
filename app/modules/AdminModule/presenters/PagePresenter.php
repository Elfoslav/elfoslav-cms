<?php

namespace AdminModule;

use \Nette\Application\BadRequestException,
	\Models\PageHelper;
/**
 * Page presenter
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavCMS
 */

class PagePresenter extends SecuredPresenter
{
	public function renderDefault() {
		$this->template->pages = $this->editablePageRepository->findAll();
	}

	public function actionUpdate($id) {
		$page = $this->editablePageRepository->find($id);
		if(!$page) {
			throw new BadRequestException('Page not found');
		}
		$this['editPageForm']->setDefaults($page->toArray());
	}

	public function createComponentCreatePageForm() {
		$form = new \Forms\PageForm;
		$form->onSuccess[] = callback($this, 'createPageSubmitted');
		return $form;
	}

	public function createPageSubmitted($form) {
		$values = $form->getValues(TRUE);

		$existingPage = $this->editablePageRepository->findOneBy(array('title' => $values['title']));
		if($existingPage) {
			$this->flashMessage($this->translator->translate('Stránka s rovnakým názvom už existuje'), 'error');
			return;
		}

		$newPage = new \Entities\EditablePage();
		$newPage->fromArray($values);

		$this->em->persist($newPage);
		try {
			$this->em->flush();
		} catch (Exception $e) {
			//TODO log exception
			throw $e;
		}
		$this->flashMessage($this->translator->translate('Stránka bola vytvorená'));
		$this->redirect('this');
	}

	public function createComponentEditPageForm() {
		$form = new \Forms\PageForm;
		$form->addHidden('id');
		$form->onSuccess[] = callback($this, 'editPageSubmitted');
		return $form;
	}

	public function editPageSubmitted($form) {
		$values = $form->getValues(TRUE);
		$id = $values['id'];
		unset($values['id']);

		$page = $this->editablePageRepository->find($id);
		$page->fromArray($values);

		try {
			$this->em->flush();
		} catch (Exception $e) {
			//TODO log exception
			throw $e;
		}

		$this->flashMessage($this->translator->translate('Stránka upravená'));
		$this->redirect('this');
	}
}
