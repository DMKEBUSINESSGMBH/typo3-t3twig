<?php
namespace DMK\T3twig\Util;

/**
 * Class TwigUtil
 *
 * @package DMK\T3twig\Util
 */
class TwigUtil {

	public static function getTwigTemplate(\tx_rnbase_configurations $configurations, $template, $debug = false)
	{
		$utility = \tx_rnbase_util_Typo3Classes::getGeneralUtilityClass();
		$filePath = $utility::getFileAbsFileName($configurations->getTemplatePath());

		/**
		 * @TODO: take care of debug configuration
		 * @TODO: Add TwigCaching
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
