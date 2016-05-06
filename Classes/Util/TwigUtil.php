<?php

namespace DMK\T3twig\Util;

/**
 * Class TwigUtil
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig\Util
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class TwigUtil
{
	/**
	 * Returns a template instance representing the given template name
	 *
	 * @param \tx_rnbase_configurations $configurations rn_base configuration
	 * @param string                    $template       template name
	 * @param bool                      $debug          enable debug
	 *
	 * @return \Twig_TemplateInterface
	 */
	public static function getTwigTemplate($configurations, $template, $debug = true)
	{
		$utility = \tx_rnbase_util_Typo3Classes::getGeneralUtilityClass();
		$filePath = $utility::getFileAbsFileName($configurations->getTemplatePath());

		/**
		 * Some ToDos
		 *
		 * @TODO: take care of debug configuration
		 * @TODO: Add TwigCaching
		 * @TODO: Handle twig extension, filter etc. includes via TS
		 */
		$loader = new \Twig_Loader_Filesystem($filePath);
		$twig = new \Twig_Environment(
			$loader,
			[
				'debug' => true,
			]
		);

		$twig->addExtension(new \Twig_Extension_Debug());

		return $twig->loadTemplate($template);
	}
}
