<?php

namespace Forms;
/**
 * Page form.
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavCMS
 */
class PageForm extends \Nette\Application\UI\Form {

	public function __construct(\Nette\ComponentModel\IContainer $parent = NULL, $name = NULL) {
		parent::__construct($parent, $name);

		$this->addText('title', 'Názov')
			->setRequired('Názov je povinný');
		$this->addText('slug', 'Slug');
		$this->addTextarea('content', 'Obsah')
			->setRequired('Obsah je povinný');

		$this->addCheckbox('published', 'publikovať');

		$this->addSubmit('submit', 'Uložiť');
	}
}
