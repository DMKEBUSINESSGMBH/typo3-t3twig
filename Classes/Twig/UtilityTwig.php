<?php

namespace DMK\T3twig\Twig;

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

use \TYPO3\CMS\Core\Exception;
use DMK\T3twig\Twig\Loader\T3FileSystem;

/**
 * Class TwigUtil
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class UtilityTwig
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
        return new T3FileSystem($templateDir);
    }

    /**
     * Returns an instance of twig environment
     *
     * @param \Twig_Loader_Filesystem $twigLoaderFilesystem twig loader filesystem
     * @param bool                    $debug                enable debug
     *
     * @return EnvironmentTwig
     */
    public static function getTwigEnvironment(
        \Twig_Loader_Filesystem $twigLoaderFilesystem,
        $debug = true
    ) {
        /**
         * Some ToDos
         *
         * @TODO: take care of debug configuration
         */
        $twigEnv = new EnvironmentTwig(
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
     * @param array                   $paths
     *
     * @throws \Twig_Error_Loader
     */
    public static function injectTemplatePaths(
        \Twig_Loader_Filesystem $twigLoaderFilesystem,
        array $paths
    ) {
        foreach ($paths as $namespace => $path) {
            $twigLoaderFilesystem->addPath(
                \tx_rnbase_util_Files::getFileAbsFileName($path),
                $namespace
            );
        }
    }

    /**
     * Inject Twig Extensions by TS Config
     *
     * @param EnvironmentTwig $environment
     * @param array           $extensions
     *
     * @throws \TYPO3\CMS\Core\Exception
     */
    public static function injectExtensions(
        EnvironmentTwig $environment,
        array $extensions
    ) {
        foreach ($extensions as $extension => $value) {
            /**
             * @var \Twig_Extension $extInstance
             */
            $extInstance = \tx_rnbase::makeInstance($value);

            /**
             * Is it a valid twig extension?
             */
            if (!$extInstance instanceof \Twig_ExtensionInterface) {
                throw new Exception(
                    sprintf(
                        'Twig extension must be an instance of Twig_ExtensionInterface; "%s" given.',
                        is_object($extInstance) ? get_class($extInstance) : gettype($extInstance)
                    )
                );
            }

            /**
             * Is extension already enabled?
             */
            if ($environment->hasExtension($extInstance->getName())) {
                continue;
            }

            $environment->addExtension($extInstance);
        }
    }
}
