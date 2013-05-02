<?php

namespace AdminModule;
/**
 * Blog presenter
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavNetteSandbox
 */
use \Entities\Article;

class BlogPresenter extends SecuredPresenter
{
	public function createComponentBlogForm() {
		$form = new \Nette\Application\UI\Form();

		$form->addText('title', 'Názov článku')
			->setRequired('Názov je povinný');
		$form->addTextarea('content', 'Obsah')
			->setRequired('Obsah je povinný');
		$form->addCheckbox('published', 'publikovať');

		$form->onSuccess[] = callback($this, 'blogFormSubmitted');

		$form->addSubmit('submit', 'Uložiť');

		return $form;
	}

	public function blogFormSubmitted($form) {
		$values = $form->getValues(true);
		$article = new Article();
		$article->fromArray($values);
		dump($article);
		$this->em->persist($article);
		$this->em->flush();
		$this->flashMessage('Article created');
		$this->redirect(':Front:Blog:default');
	}
}
