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
			throw new BadRequestException('Article id is empty');
		}

		$article = $this->articleRepository->find($id);

		if(!$article) {
			throw new BadRequestException('Article not found');
		}

		$defaults = $article->toArray();

		$categories = $this->articleCategoryRepository->findAll();
		$categoryNames = PageHelper::getPagesNamesString($categories);
		$articleCategories = PageHelper::getPagesNamesString($article->getCategories());
		$defaults['categoriesMultiple'] = $articleCategories;
		$this['editArticleForm']['categoriesMultiple']->setAttribute('data-autocomplete', $categoryNames);

		$tags = $this->tagRepository->findAll();
		$tagNames = PageHelper::getPagesNamesString($tags);
		$articleTags = PageHelper::getPagesNamesString($article->getTags());
		$defaults['tagsMultiple'] = $articleTags;
		$this['editArticleForm']['tagsMultiple']->setAttribute('data-autocomplete', $tagNames);

		$this['editArticleForm']->setDefaults($defaults);

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

		if(!$values['created']) {
			$values['created'] = new \DateTime;
		}

		$article = new Article();
		$article->fromArray($values);
		if(!$article->created) {
			$article->created = new \Nette\DateTime;
		}

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

		$categoriesNamesArr = PageHelper::getPagesNamesArray($categoriesNames);
		$article->setCategories(array());
		foreach($categoriesNamesArr as $catName) {
			$existingCat = $this->articleCategoryRepository->findOneBy(array('title' => $catName));
			if($existingCat) {
				if(!$article->hasCategory($existingCat)) {
					$article->addCategory($existingCat);
				}
			}
		}

		$article->setTags(array());
		$tagsNamesArr = PageHelper::getPagesNamesArray($tagsNames);
		foreach($tagsNamesArr as $tagName) {
			$existingTag = $this->tagRepository->findOneByTitle($tagName);
			if($existingTag) {
				$article->addTag($existingTag);
			}
		}

		$newCategories = $this->createCategories($categoriesNames, $article);
		$newTags = $this->createTags($tagsNames, $article);

		$article->fromArray($values);

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
	 * @return array - empty array if $categoriesNames or $article is empty
	 * 				   array of Entities\ArticleCategory otherwise
	 */
	private function createCategories($categoriesNames, $article) {
		if(!$categoriesNames || !$article) {
			return array();
		}
		$categoriesArr = PageHelper::getPagesNamesArray($categoriesNames);
		$categoryRepo = $this->articleCategoryRepository;

		$createdCategories = array();
		foreach($categoriesArr as $cat) {
			$category = $categoryRepo->findByTitle($cat);
			if(!$category) {
				$newCategory = new \Entities\ArticleCategory();
				$newCategory->setTitle($cat);
				$newCategory->setSlug($cat);
				$this->em->persist($newCategory);
				$article->addCategory($newCategory);
				$createdCategories[] = $newCategory;
			}
		}
		return $createdCategories;
	}

	/**
	 * Create article tags if they do not already exist
	 *
	 * @param string $tagsNames - tags names in format "name1,name2,..."
	 * @param $article
	 * @return array - empty array if $tagsNames or $article is empty
	 * 				   array of Entities\Tag otherwise
	 */
	private function createTags($tagsNames, $article) {
		if(!$tagsNames || !$article) {
			return array();
		}
		$tagsArr = PageHelper::getPagesNamesArray($tagsNames);
		$tagRepo = $this->tagRepository;

		$createdTags = array();
		foreach($tagsArr as $tag) {
			$tagEntity = $tagRepo->findByTitle($tag);
			if(!$tagEntity) {
				$newTag = new \Entities\Tag();
				$newTag->setTitle($tag);
				$newTag->setSlug($tag);
				$this->em->persist($newTag);
				$article->addTag($newTag);
				$createdTags[] = $newTag;
			}
		}
		return $createdTags;
	}
}
