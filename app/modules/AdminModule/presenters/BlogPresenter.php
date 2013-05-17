<?php

namespace AdminModule;

use Nette\Application\BadRequestException,
	Models\PageHelper,
	Entities\Article;;
/**
 * Blog presenter
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavCMS
 */

class BlogPresenter extends SecuredPresenter
{
	public function renderDefault() {
		$this->template->articles = $this->articleRepository->findAll();
	}

	public function actionUpdate($id) {
		if(!$id) {
			throw new BadRequestException('Article id is NULL');
		}

		$article = $this->articleRepository->find($id);

		if(!$article) {
			throw new BadRequestException('Article not found');
		}

		$this['editArticleForm']->setDefaults($article->toArray());
		$categoryNames = PageHelper::getPagesNamesString($article->getCategories());
		$this['editArticleForm']['categoriesMultiple']->setValue($categoryNames)
			->setAttribute('data-autocomplete', $categoryNames);

		$tagNames = PageHelper::getPagesNamesString($article->getTags());
		$this['editArticleForm']['tagsMultiple']->setValue($tagNames)
			->setAttribute('data-autocomplete', $tagNames);

		$this->template->article = $article;
	}

	public function actionCreate() {
		$categoryRepo = $this->articleCategoryRepository;
		$categories = $categoryRepo->findAll();
		$categoryNames = PageHelper::getPagesNamesString($categories);
		$this['createArticleForm']['categoriesMultiple']
			->setAttribute('data-autocomplete', $categoryNames);

		$tags = $this->tagRepository->findAll();
		$tagNames = PageHelper::getPagesNamesString($tags);
		$this['createArticleForm']['tagsMultiple']
			->setAttribute('data-autocomplete', $tagNames);
	}

	public function createComponentBaseArticleForm() {
		$form = new \Forms\ArticleForm;

		return $form;
	}

	public function createComponentCreateArticleForm() {
		$form = $this->createComponentBaseArticleForm();
		$form->onSuccess[] = callback($this, 'createArticleFormSubmitted');

		return $form;
	}

	public function createArticleFormSubmitted($form) {
		$values = $form->getValues(true);
		$categoriesNames = $values['categoriesMultiple'];
		$tagsNames = $values['tagsMultiple'];
		unset($values['categoriesMultiple'], $values['tagsMultiple']);

		$existingPage = $this->basePageRepository->findOneBy(array('title' => $values['title']));
		if($existingPage) {
			$this->flashMessage($this->translator->translate('Stránka s rovnakým názvom už existuje'), 'error');
			return;
		}

		$article = new Article();
		$article->fromArray($values);

		$this->createCategories($categoriesNames, $article);
		$this->createTags($tagsNames, $article);

		$this->em->persist($article);
		try {
			$this->em->flush();
		} catch (\Exception $e) {
			//TODO: log exception/send mail
			throw $e;
		}
		$this->flashMessage($this->translator->translate('Článok vytvorený'));
		$this->redirect(':Admin:Blog:default');
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
		$categoriesNames = $values['categoriesMultiple'];
		$tagsNames = $values['tagsMultiple'];
		unset($values['id'], $values['categoriesMultiple'], $values['tagsMultiple']);

		$article = $this->articleRepository->find($id);
		$article->fromArray($values);

		$this->createCategories($categoriesNames, $article);
		$this->createTags($tagsNames, $article);

		try {
			$this->em->flush();
		} catch (\Exception $e) {
			//TODO: log exception/send mail
			throw $e;
		}

		$this->flashMessage($this->translator->translate('Článok upravený'));

		$this->redirect('this');
	}

	/**
	 * Create article categories if they do not already exist
	 *
	 * @param string $categoriesNames - categories names in format "name1,name2,..."
	 * @param $article
	 */
	private function createCategories($categoriesNames, $article) {
		$categoriesArr = PageHelper::getPagesNamesArray($categoriesNames);
		$categoryRepo = $this->articleCategoryRepository;
		foreach($categoriesArr as $cat) {
			$category = $categoryRepo->findByTitle($cat);
			if(!$category) {
				$newCategory = new \Entities\ArticleCategory();
				$newCategory->setTitle($cat);
				$newCategory->setSlug($cat);
				$this->em->persist($newCategory);
				$article->addCategory($newCategory);
			}
		}
	}

	/**
	 * Create article tags if they do not already exist
	 *
	 * @param string $tagsNames - tags names in format "name1,name2,..."
	 * @param $article
	 */
	private function createTags($tagsNames, $article) {
		$tagsArr = PageHelper::getPagesNamesArray($tagsNames);
		$tagRepo = $this->tagRepository;
		foreach($tagsArr as $tag) {
			$tagEntity = $tagRepo->findByTitle($tag);
			if(!$tagEntity) {
				$newTag = new \Entities\Tag();
				$newTag->setTitle($tag);
				$newTag->setSlug($tag);
				$this->em->persist($newTag);
				$article->addTag($newTag);
			}
		}
	}
}
