<?php

namespace Forms;

use ElfoslavCMS\Components\Date;

/**
 * Page form.
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavCMS
 */
class PageForm extends \Nella\Forms\Form {

	public function __construct(\Nette\ComponentModel\IContainer $parent = NULL, $name = NULL) {
		parent::__construct($parent, $name);

		$this->getElementPrototype()->novalidate = 'novalidate';

		$this->addText('title', 'Názov')
			->setRequired('Názov je povinný');
		$this->addText('slug', 'Slug');
		$date = $this->addDate('created', 'Dátum vytvorenia');
		$this->addTextarea('description', 'Popis')
			->setAttribute('rows', 5);
		$this->addTextarea('content', 'Obsah')
			->setAttribute('class', 'tinymce')
			->setRequired('Obsah je povinný');

		$this->addCheckbox('published', 'publikovať');

		$this->addSubmit('submit', 'Uložiť');
	}

	/**
	* Adds date input control to the form.
	*
	* @param string control name
	* @param string label
	* @param int width of the control
	* @return Controls\Date
	*/
	public function addDate($name, $label = NULL, $cols = NULL)
	{
	return $this[$name] = new Date($label, $cols, NULL);
	}
}
