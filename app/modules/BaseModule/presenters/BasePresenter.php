<?php

namespace BaseModule;

use Nette\Templating\Filters\Haml,
	Nette\Latte\Engine;

/**
 * Base class for all application presenters.
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavCMS
 */
abstract class BasePresenter extends \Nette\Application\UI\Presenter
{
	/** @persistent */
	public $lang = 'sk';

	public function createTemplate($class = NULL) {
		$template = parent::createTemplate($class);

		// if not set, the default language will be used
		if (!isset($this->lang)) {
			$this->lang = $this->translator->getLang();
		} else {
			$this->translator->setLang($this->lang);
		}

		$template->setTranslator($this->translator);

		/** search helper will highlight searched word and shows text around this word */
		$template->registerHelper('search', function($s, $search, $charCount = 200) {
			$searchPos = strpos($s, $search);

			$start = (($searchPos - $charCount) < 0) ? 0 : $searchPos - $charCount;
			$end = $searchPos + $charCount;

			$textLength = strlen($s);

			$s = substr($s, $start, $end);
			if($start != 0) {
				$s = '...' . $s;
			}
			if($end < $textLength) {
				$s = $s . '...';
			}

			$s = str_replace($search, '<strong>' . $search . '</strong>', $s);
			return $s;
		});

		return $template;
	}

	public function templatePrepareFilters($template)
	{
		$template->registerFilter(new Haml);
		parent::templatePrepareFilters($template);
	}

	/** @var Doctrine\ORM\EntityManager */
	protected $em;

	public function injectEntityManager(\Doctrine\ORM\EntityManager $em)
	{
		if ($this->em) {
			throw new \Nette\InvalidStateException('Entity manager has already been set');
		}
		$this->em = $em;
		return $this;
	}

	/** @var GettextTranslator\Gettext */
	protected $translator;

	/**
	 * @param GettextTranslator\Gettext
	 */
	public function injectTranslator(\GettextTranslator\Gettext $translator)
	{
		if ($this->translator) {
			throw new Nette\InvalidStateException('Translator has already been set');
		}
		$this->translator = $translator;
	}

	/** @var Repositories\ArticleRepository */
	protected $articleRepository;

	/**
	 * @param Repositories\ArticleRepository
	 */
	public function injectArticleRepository(\Repositories\ArticleRepository $articleRepository)
	{
		if ($this->articleRepository) {
			throw new Nette\InvalidStateException('ArticleRepository has already been set');
		}
		$this->articleRepository = $articleRepository;
	}

	/** @var Repositories\ArticleCategoryRepository */
	protected $articleCategoryRepository;

	/**
	 * @param Repositories\ArticleCategoryRepository
	 */
	public function injectArticleCategoryRepository(\Repositories\ArticleCategoryRepository $articleCategoryRepository)
	{
		if ($this->articleCategoryRepository) {
			throw new Nette\InvalidStateException('ArticleCategoryRepository has already been set');
		}
		$this->articleCategoryRepository = $articleCategoryRepository;
	}

	/** @var Repositories\TagRepository */
	protected $tagRepository;

	/**
	 * @param Repositories\TagRepository
	 */
	public function injectTagRepository(\Repositories\TagRepository $tagRepository)
	{
		if ($this->tagRepository) {
			throw new Nette\InvalidStateException('TagRepository has already been set');
		}
		$this->tagRepository = $tagRepository;
	}

	/** @var Repositories\EditablePageRepository */
	protected $editablePageRepository;

	/**
	 * @param Repositories\EditablePageRepository
	 */
	public function injectEditablePageRepository(\Repositories\EditablePageRepository $editablePageRepository)
	{
		if ($this->editablePageRepository) {
			throw new Nette\InvalidStateException('EditablePageRepository has already been set');
		}
		$this->editablePageRepository = $editablePageRepository;
	}

	/** @var Repositories\BasePageRepository */
	protected $basePageRepository;

	/**
	 * @param Repositories\BasePageRepository
	 */
	public function injectBasePageRepository(\Repositories\BasePageRepository $basePageRepository)
	{
		if ($this->basePageRepository) {
			throw new Nette\InvalidStateException('BasePageRepository has already been set');
		}
		$this->basePageRepository = $basePageRepository;
	}
}
