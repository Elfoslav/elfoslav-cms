<?php

namespace Forms;
/**
 * Article form.
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavCMS
 */
class ArticleForm extends PageForm {

	public function __construct(\Nette\ComponentModel\IContainer $parent = NULL, $name = NULL) {
		parent::__construct($parent, $name);

		$categories = new \Nette\Forms\Controls\TextInput('Kategórie');
		$categories->setAttribute('class', 'select2 multiple');

		$tags = new \Nette\Forms\Controls\TextInput('Tagy');
		$tags->setAttribute('class', 'select2 multiple');

		$this->addComponent($categories, 'categoriesMultiple', 'submit');
		$this->addComponent($tags, 'tagsMultiple', 'submit');
	}
}
