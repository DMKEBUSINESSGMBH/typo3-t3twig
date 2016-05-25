<?php

namespace DMK\T3twig\Twig;

use DMK\T3twig\Util\T3twigEnvironment;

/**
 * Class TSParserExtension
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig\Twig
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 *           Michael Wagner <dev@dmk-ebusiness.de>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class TSParserExtension extends \Twig_Extension
{
	/**
	 * @return array
	 */
	public function getFilters()
	{
		return [
			new \Twig_SimpleFilter(
				't3parsefield', [$this, 'parseField'],
				['needs_environment' => true, 'is_safe' => ['html'],]
			),
		];
	}

	/**
	 * @param T3twigEnvironment $env
	 * @param array             $record
	 * @param string            $field
	 *
	 * @return string
	 */
	public function parseField(T3twigEnvironment $env, $record, $field)
	{
		$configurations = $env->getConfigurations();
		$confId         = $env->getConfId();
		$conf           = $configurations->get($confId);
		$cObj           = $configurations->getCObj();
		$tmp            = $cObj->data;
		$value          = $record[ $field ];

		$cObj->data = $record;

		// For DATETIME there is a special treatment to treat empty values
		if ($conf[ $field ] == 'DATETIME' && $conf[ $field.'.' ]['ifEmpty'] && ! $value) {
			$value = $conf[ $field.'.' ]['ifEmpty'];
		} elseif ($conf[ $field ]) {
			$cObj->setCurrentVal($value);
			$value = $cObj->cObjGetSingle($conf[ $field ], $conf[ $field.'.' ]);
			$cObj->setCurrentVal(false);
		} elseif ($conf[ $field ] == 'CASE') {
			$value = $cObj->CASEFUNC($conf[ $field.'.' ]);
		} else {
			$value = $cObj->stdWrap($value, $conf[ $field.'.' ]);
		}

		$cObj->data = $tmp;

		return $value;
	}

	/**
	 * Get Extension name
	 *
	 * @return string
	 */
	public function getName()
	{
		return 't3twig_tsParserExtension';
	}
}
