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
	 * Returns an instance of twig loader filesystem
	 *
	 * @param string $templateDir template directory
	 *
	 * @return \Twig_Loader_Filesystem
	 */
	public static function getTwigLoaderFilesystem($templateDir)
	{
		return new \Twig_Loader_Filesystem($templateDir);
	}

	/**
	 * Returns an instance of twig environment
	 *
	 * @param \Twig_Loader_Filesystem $twigLoaderFilesystem twig loader filesystem
	 * @param bool                    $debug                enable debug
	 *
	 * @return T3twigEnvironment
	 */
	public static function getTwigEnvironment(\Twig_Loader_Filesystem $twigLoaderFilesystem, $debug = true)
	{
		/**
		 * Some ToDos
		 *
		 * @TODO: take care of debug configuration
		 */
		$twigEnv = new T3twigEnvironment(
			$twigLoaderFilesystem,
			[
				'debug' => $debug,
				'cache' => PATH_site.'typo3temp/t3twig',
			]
		);

		return $twigEnv;
	}

	/**
	 * Inject twig template paths with namespace
	 *
	 * @param \Twig_Loader_Filesystem $twigLoaderFilesystem
	 * @param array $paths
	 *
	 * @throws \Twig_Error_Loader
	 */
	public static function injectTemplatePaths(\Twig_Loader_Filesystem $twigLoaderFilesystem, array $paths)
	{
		foreach ($paths as $namespace => $path){
			$twigLoaderFilesystem->addPath(\tx_rnbase_util_Files::getFileAbsFileName($path), $namespace);
		}
	}

	/**
 	 * Inject Twig Extensions by TS Config
	 *
	 * @param T3twigEnvironment $environment
	 * @param array $extensions
	 *
	 * @throws \TYPO3\CMS\Core\Exception
	 */
	public static function injectExtensions(T3twigEnvironment $environment, array $extensions)
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
