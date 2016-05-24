<?php

namespace DMK\T3twig\Twig;

use DMK\T3twig\Util\T3twigEnvironment;

/**
 * Class ImageExtension
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig\Twig
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class ImageExtension extends \Twig_Extension
{
	/**
	 * @return array
	 */
	public function getFilters()
	{
		return [
			new \Twig_SimpleFilter('getMediaObjects', [$this, 'getMediaObjects']),
			new \Twig_SimpleFilter(
				't3images', [$this, 'renderImage'],
				['needs_environment' => true]
			),
			new \Twig_SimpleFilter(
				't3imageFromTS', [$this, 'renderImageFromTyposcript'],
				['needs_environment' => true, 'is_safe' => ['html']]
			),
		];
	}

	/**
	 * @return array
	 */
	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('getGenericMediaObjects', [$this, 'getGenericMediaObjects']),
		];
	}

	/**
	 * @param T3twigEnvironment                       $env
	 * @param \Tx_Rnbase_Domain_Model_DomainInterface $model
	 * @param string                                  $refField
	 *
	 * @return array
	 */
	public function renderImage(
		T3twigEnvironment $env,
		\Tx_Rnbase_Domain_Model_DomainInterface $model,
		$refField = 'images'
	) {
		$configurations = $env->getConfigurations();
		$confId         = $env->getConfId();
		$images         = [];
		$fileRefs       = \tx_rnbase_util_TSFAL::fetchReferences($model->getTableName(), $model->getUid(), $refField);

		foreach ($fileRefs as $fileRef) {
			$images[] = $configurations->getCObj()->cImage(
				$fileRef,
				$configurations->get($confId.$refField.'.')
			);
		}

		return $images;
	}

	/**
	 * @param T3twigEnvironment $env
	 * @param string            $tsPath
	 *
	 * @return string
	 */
	public function renderImageFromTyposcript(T3twigEnvironment $env, $tsPath)
	{
		$configurations = $env->getConfigurations();

		return $configurations->getCObj()->cImage(
			$configurations->get($tsPath.'.file'),
			$configurations->get($tsPath.'.')
		);
	}


	/**
	 * Fetches FAL records and return as array of tx_rnbase_model_media
	 *
	 * @param \Tx_Rnbase_Domain_Model_DomainInterface $model
	 * @param                                         $refField
	 *
	 * @return array[tx_rnbase_model_media]
	 */
	public function getMediaObjects(\Tx_Rnbase_Domain_Model_DomainInterface $model, $refField = 'images')
	{
		return $this->fetchFiles($model->getTableName(), $model->getUid(), $refField);
	}

	/**
	 * @param        $table
	 * @param        $uid
	 * @param string $refField
	 *
	 * @return array
	 */
	public function getGenericMediaObjects($table, $uid, $refField = 'images')
	{
		return $this->fetchFiles($table, $uid, $refField);
	}

	/**
	 * @param $table
	 * @param $uid
	 * @param $refField
	 *
	 * @return array
	 */
	protected function fetchFiles($table, $uid, $refField)
	{
		return \tx_rnbase_util_TSFAL::fetchFiles($table, $uid, $refField);
	}

	/**
	 * Get Extension name
	 *
	 * @return string
	 */
	public function getName()
	{
		return 't3twig_imageExtension';
	}
}
