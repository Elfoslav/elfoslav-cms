<?php

namespace AdminModule;
/**
 * Secured presenter
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavNetteSandbox
 */
class SecuredPresenter extends BasePresenter
{
	public function startup() {
		parent::startup();

		if(!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in');
		}
	}
}
