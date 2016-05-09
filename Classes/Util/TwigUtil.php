<?php

namespace DMK\T3twig\Util;

use \TYPO3\CMS\Core\Exception;

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

		$twig = self::injectExtensions($twig);

		return $twig->loadTemplate($template);
	}

	/**
 	 * Inject Twig Extensions by TS Config
	 *
	 * @param \Twig_Environment $environment
	 *
	 * @return \Twig_Environment
	 * @throws \TYPO3\CMS\Core\Exception
	 */
	private function injectExtensions(\Twig_Environment $environment)
	{
		/** Get Extension Config */
		$extensions = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_t3twig.']['twig_extensions.'];

		foreach ($extensions as $extension => $value){
			/** @var \Twig_Extension $extInstance */
			$extInstance = \tx_rnbase::makeInstance($value);

			/** Is it a valide twig extension? */
			if (! $extInstance instanceof \Twig_ExtensionInterface) {
				throw new Exception(sprintf(
					'Twig extension must be an instance of Twig_ExtensionInterface; "%s" given,',
					is_object($extInstance) ? get_class($extInstance) : gettype($extInstance)
				));
			}

			/** Is extension already enabled? */
			if ($environment->hasExtension($extInstance->getName())){
				continue;
			}

			$environment->addExtension($extInstance);
		}

		return $environment;
	}
}
