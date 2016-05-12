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
	 * @param string $template template name
	 * @param bool   $debug    enable debug
	 *
	 * @return \Twig_TemplateInterface
	 */
	public static function getTwigTemplate($template, $debug = true)
	{
		$filePath = dirname($template);

		/**
		 * Some ToDos
		 *
		 * @TODO: take care of debug configuration
		 */
		$loader = new \Twig_Loader_Filesystem($filePath);
		$twig = new \Twig_Environment(
			$loader,
			[
				'debug' => true,
				'cache' => PATH_site.'typo3temp/t3twig',
			]
		);

		return $twig->loadTemplate(basename($template));
	}

	/**
 	 * Inject Twig Extensions by TS Config
	 *
	 * @param \Twig_Environment $environment
	 * @param array $extensions
	 *
	 * @return \Twig_Environment
	 * @throws \TYPO3\CMS\Core\Exception
	 */
	public static function injectExtensions(\Twig_Environment $environment, array $extensions)
	{
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
	}
}
