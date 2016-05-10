<?php

namespace DMK\T3twig\Hook;

use \TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Resource\Folder;

/**
 * Class DataHandler
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig\Hook
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class DataHandler implements SingletonInterface
{
	/**
	 * @param array $parameters
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function clearTwigCache(array $parameters = [])
	{
		self::recursivelyRemoveDirectory(PATH_site.'typo3temp/t3twig');

		return true;
	}

	/**
	 * remove cache directory
	 * http://stackoverflow.com/a/31008113
	 *
	 * @param $source
	 * @param bool $removeOnlyChildren
	 *
	 * @throws \Exception
	 */
	private static function recursivelyRemoveDirectory($source, $removeOnlyChildren = true)
	{
		if (empty($source) || file_exists($source) === false) {
			throw new \Exception("File does not exist: '$source'");
		}

		if (is_file($source) || is_link($source)) {
			if (false === unlink($source)) {
				throw new \Exception("Cannot delete file '$source'");
			}
		}

		$files = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
			\RecursiveIteratorIterator::CHILD_FIRST
		);

		foreach ($files as $fileInfo) {
			if ($fileInfo->isDir()) {
				if (self::recursivelyRemoveDirectory($fileInfo->getRealPath()) === false) {
					throw new \Exception("Failed to remove directory '{$fileInfo->getRealPath()}'");
				}
				if (false === rmdir($fileInfo->getRealPath())) {
					throw new \Exception("Failed to remove empty directory '{$fileInfo->getRealPath()}'");
				}
			} else {
				if (unlink($fileInfo->getRealPath()) === false) {
					throw new \Exception("Failed to remove file '{$fileInfo->getRealPath()}'");
				}
			}
		}

		if ($removeOnlyChildren === false) {
			if (false === rmdir($source)) {
				throw new \Exception("Cannot remove directory '$source'");
			}
		}
	}
}
