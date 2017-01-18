<?php

namespace DMK\T3twig\Hook;

/***************************************************************
 * Copyright notice
 *
 * (c) 2016 DMK E-BUSINESS GmbH <dev@dmk-ebusiness.de>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use \TYPO3\CMS\Core\SingletonInterface;

/**
 * Class DataHandler
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @author   Michael Wagner
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
     * @param      $source
     * @param bool $removeOnlyChildren
     *
     * @throws \Exception
     */
    private static function recursivelyRemoveDirectory(
        $source,
        $removeOnlyChildren = true
    ) {
        if (empty($source) || file_exists($source) === false) {
            throw new \Exception("File does not exist: '$source'");
        }

        if (is_file($source) || is_link($source)) {
            if (false === unlink($source)) {
                throw new \Exception("Cannot delete file '$source'");
            }
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $source,
                \RecursiveDirectoryIterator::SKIP_DOTS
            ),
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
