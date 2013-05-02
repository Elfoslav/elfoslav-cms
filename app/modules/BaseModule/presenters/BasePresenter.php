<?php

namespace BaseModule;

use Nette\Templating\Filters\Haml,
	Nette\Latte\Engine;

/**
 * Base class for all application presenters.
 *
 * @author     Tomáš Hromník
 * @package    MyApplication
 */
abstract class BasePresenter extends \Nette\Application\UI\Presenter
{
	/** @persistent */
    public $lang;

	public function createTemplate($class = NULL) {
		$template = parent::createTemplate($class);

		// if not set, the default language will be used
        if (!isset($this->lang)) {
            $this->lang = $this->translator->getLang();
        } else {
            $this->translator->setLang($this->lang);
        }

        $template->setTranslator($this->translator);

		return $template;
	}

	public function templatePrepareFilters($template)
	{
		$template->registerFilter(new Haml);
		$template->registerFilter(new Engine);
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
}
