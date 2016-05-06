<?php

namespace DMK\T3twig\Action;

/**
 * Class BaseTwigAction
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig\Action
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
abstract class BaseTwigAction extends \tx_rnbase_action_BaseIOC
{
	/**
	 * Returns the name of the view class
	 *
	 * @return string
	 */
	public function getViewClassName()
	{
		return 'DMK\T3twig\View\BaseTwigView';
	}

	/**
	 * Returns the template file name
	 *
	 * @return string
	 */
	public function getTemplateFileName()
	{
		return $this->getTemplateName();
	}
}
