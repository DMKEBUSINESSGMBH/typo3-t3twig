<?php

namespace DMK\T3twig\Action;

/**
 * Class BaseTwigAction
 * @package DMK\T3twig\Action
 */
abstract class BaseTwigAction extends \tx_rnbase_action_BaseIOC
{
	/**
	 * Gibt den Name der zugehörigen View-Klasse zurück
	 *
	 * @return string
	 */
	public function getViewClassName() {
		return 'DMK\T3twig\View\BaseTwigView';
	}

	/**
	 * @return string
	 */
	public function getTemplateFileName()
	{
		return $this->getTemplateName();
	}
}
