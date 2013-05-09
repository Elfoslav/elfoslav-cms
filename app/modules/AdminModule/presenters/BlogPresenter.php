<?php

namespace AdminModule;

use \Nette\Application\BadRequestException;
/**
 * Blog presenter
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavNetteSandbox
 */
use \Entities\Article;

class BlogPresenter extends SecuredPresenter
{
	public function actionUpdate($id) {
		if(!$id) {
			throw new BadRequestException('Article id is NULL');
		}

		$article = $this->articleRepository->find($id);

		if(!$article) {
			throw new BadRequestException('Article not found');
		}

		$this['editArticleForm']->setDefaults($article->toArray());
	}

	public function createComponentBaseArticleForm() {
		$form = new \Nette\Application\UI\Form();

		$form->addText('title', 'Názov článku')
			->setRequired('Názov je povinný');
		$form->addTextarea('content', 'Obsah')
			->setRequired('Obsah je povinný');
		$form->addCheckbox('published', 'publikovať');

		$form->addSubmit('submit', 'Uložiť');

		return $form;
	}

	public function createComponentCreateArticleForm() {
		$form = $this->createComponentBaseArticleForm();
		$form->onSuccess[] = callback($this, 'createArticleFormSubmitted');

		return $form;
	}

	public function createArticleFormSubmitted($form) {
		$values = $form->getValues(true);
		$article = new Article();
		$article->fromArray($values);
		dump($article);
		$this->em->persist($article);
		$this->em->flush();
		$this->flashMessage('Article created');
		$this->redirect(':Front:Blog:default');
	}

	public function createComponentEditArticleForm() {
		$form = $this->createComponentBaseArticleForm();
		$form->addHidden('id');
		$form->onSuccess[] = callback($this, 'editArticleFormSubmitted');

		return $form;
	}

	public function editArticleFormSubmitted($form) {
		$values = $form->getValues(true);
		$id = $values['id'];
		unset($values['id']);

		$article = $this->articleRepository->find($id);
		$article->fromArray($values);

		$this->em->flush();

		$this->flashMessage('Article updated');

		$this->redirect('this');
	}
}
