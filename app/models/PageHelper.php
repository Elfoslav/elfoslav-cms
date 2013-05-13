<?php

namespace Models;

/**
 * PageHelper
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavCMS
 */
class PageHelper extends \Nette\Object {

	/**
      * Static class - cannot be instantiated.
      */
    final public function __construct()
    {
        throw new Nette\StaticClassException;
    }

	/**
	 * @param array|collection
	 * @return string in format "name1,name2,..." or NULL if pages count is 0
	 */
	public static function getPagesNamesString($pages) {
		if(!(is_array($pages)) && !($pages instanceof \Doctrine\Common\Collections\Collection)) {
			throw new \Nette\InvalidArgumentException;
		}
		$str = '';
		foreach($pages as $page) {
			$str .= $page->getTitle() . ',';
		}
		$str = rtrim($str, ',');
		return ($str) ? $str : NULL;
	}

	/**
	 * @param string $pagesNames - string like "name1,name2,name3,..." without spaces between commas
	 */
	public static function getPagesNamesArray($pagesNames) {
		return explode(',', $pagesNames);
	}
}
