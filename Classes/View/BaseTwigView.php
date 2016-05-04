<?php

namespace DMK\T3twig\View;

use DMK\T3twig\Action\BaseTwigAction;
use DMK\T3twig\Util\TwigUtil;
use phpseclib\Crypt\Base;

\tx_rnbase::load('tx_rnbase_view_Base');

class BaseTwigView extends \tx_rnbase_view_Base
{
	/**
	 * @param string $template
	 * @param \ArrayObject $viewData
	 * @param \tx_rnbase_configurations $configurations
	 * @param \tx_rnbase_util_FormatUtil $formatter
	 *
	 * @return string
	 */
	function createOutput($template, &$viewData, &$configurations, &$formatter) {

		$templateName = $this->getController()->getTemplateFileName().'.html.twig';
		$template = TwigUtil::getTwigTemplate(
			$configurations, $templateName
		);

		/**
		 * @TODO: check for performance issues?!
		 */
		$result = $template->render($viewData->getArrayCopy());

		return $result;
	}

	/**
	 * @return BaseTwigAction
	 */
	function getController() {
		return parent::getController();
	}

	/**
	 * Set the used controller
	 *
	 * @param BaseTwigAction $controller
	 */
	public function setController(BaseTwigAction $controller) {
		parent::setController($controller);
	}
}
