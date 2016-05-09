<?php

namespace DMK\T3twig\View;

use DMK\T3twig\Action\BaseTwigAction;
use DMK\T3twig\Util\TwigUtil;

/**
 * Class BaseTwigView
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig\View
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class BaseTwigView extends \tx_rnbase_view_Base
{
	/**
	 * Returns rendered twig template
	 *
	 * @param string                     $template       template name
	 * @param \ArrayObject               $viewData       viewData object
	 * @param \tx_rnbase_configurations  $configurations rn_base configuration
	 * @param \tx_rnbase_util_FormatUtil $formatter      formatter
	 *
	 * @return string
	 */
	function createOutput($template, &$viewData, &$configurations, &$formatter)
	{
		$templateName = $this->getController()->getTemplateFileName().'.html.twig';
		$template = TwigUtil::getTwigTemplate(
			$configurations, $templateName, $this->getController()->getConfId()
		);

		/**
		 * Some Todos
		 *
		 * @TODO: check for performance issues?!
		 */
		$result = $template->render(
			[
				'viewData' => $viewData->getArrayCopy(),
				'configurations' => $configurations,
				'formatter' => $formatter,
			]
		);

		return $result;
	}

	/**
	 * Returns BaseTwigAction controller
	 *
	 * @return BaseTwigAction
	 */
	function getController()
	{
		return parent::getController();
	}

	/**
	 * Set the used controller and ensure type safety
	 *
	 * @param BaseTwigAction $controller
	 */
	public function setController(BaseTwigAction $controller)
	{
		parent::setController($controller);
	}
}
