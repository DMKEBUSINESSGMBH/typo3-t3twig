<?php

namespace DMK\T3twig\Twig;

use DMK\T3twig\Util\T3twigEnvironment;

/**
 * Class LanguageExtension
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig\Twig
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class LanguageExtension extends \Twig_Extension
{
	/**
	 * @return array
	 */
	public function getFilters()
	{
		return [
			new \Twig_SimpleFilter('t3trans', [$this, 'getTranslation'], ['needs_environment' => true]),
		];
	}

	/**
	 * @param T3twigEnvironment $env
	 * @param string            $label
	 * @param string            $alt
	 * @param bool              $hsc
	 *
	 * @return mixed
	 */
	public function getTranslation(T3twigEnvironment $env, $label, $alt = '', $hsc = false)
	{
		return $env->getConfigurations()->getLL($label, $alt, $hsc);
	}

	/**
	 * Get Extension name
	 *
	 * @return string
	 */
	public function getName()
	{
		return 't3twig_languageExtension';
	}
}
