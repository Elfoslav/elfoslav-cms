<?php

namespace FrontModule;

use Nette\Diagnostics\Debugger;

/**
 * Base class for all FrontModule presenters.
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavCMS
 */
abstract class BasePresenter extends \BaseModule\BasePresenter
{
	public function beforeRender() {
		$this->template->productionMode = Debugger::$productionMode;
	}
}
