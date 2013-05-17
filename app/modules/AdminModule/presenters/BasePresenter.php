<?php

namespace AdminModule;
/**
 * Base class for all AdminModule presenters.
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavCMS
 */
abstract class BasePresenter extends \BaseModule\BasePresenter
{
	/** @var \Repositories\BasePageRepository */
	protected $basePageRepository;

	/**
	 * @param \Repositories\BasePageRepository
	 */
	public function injectBasePageRepository(\Repositories\BasePageRepository $basePageRepository)
    {
		if ($this->basePageRepository) {
            throw new Nette\InvalidStateException('BasePageRepository has already been set');
        }
        $this->basePageRepository = $basePageRepository;
    }
}
