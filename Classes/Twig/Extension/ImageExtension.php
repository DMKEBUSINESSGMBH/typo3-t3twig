<?php

namespace DMK\T3twig\Twig\Extension;

/***************************************************************
 * Copyright notice
 *
 * (c) 2017 DMK E-BUSINESS GmbH <dev@dmk-ebusiness.de>
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

use DMK\T3twig\Twig\EnvironmentTwig;
use Twig\TwigFunction;

/**
 * Class ImageExtension.
 *
 * @category TYPO3-Extension
 *
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @author   Mario Seidel <dev@dmk-ebusiness.de>
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @see     https://www.dmk-ebusiness.de/
 */
class ImageExtension extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return [
        ];
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                't3fetchReferences',
                [$this, 'fetchReferences']
            ),
            new TwigFunction(
                't3image',
                [$this, 'renderImage'],
                ['needs_environment' => true, 'is_safe' => ['html']]
            ),
        ];
    }

    /**
     * @param EnvironmentTwig $env
     * @param mixed           $image
     * @param array           $arguments
     *
     * @return array
     */
    public function renderImage(
        EnvironmentTwig $env,
        $image,
        array $arguments = []
    ) {
        // backward compatibility, create the ts_config key
        if (!isset($arguments['ts_config'])) {
            $arguments['ts_config'] = $arguments;
        }

        // get Resource Object (non ExtBase version), taken from Fluid\MediaViewHelper
        if (is_object($image) && is_callable([$image, 'getOriginalResource'])) {
            // We have a domain model, so we need to fetch the FAL resource object from there
            $image = $image->getOriginalResource();
        }

        return $this->performCommand(
            function (\Sys25\RnBase\Domain\Model\DataModel $arguments) use ($env, $image) {
                return $env->getContentObject()->cImage(
                    $image,
                    $arguments->getTsConfig()
                );
            },
            $env,
            $arguments
        );
    }

    /**
     * Fetch all file references for the given table and uid.
     *
     * @param string $table
     * @param string $uid
     * @param string $refField
     *
     * @return array[\TYPO3\CMS\Core\Resource\FileReference]
     */
    public function fetchReferences($table, $uid, $refField = 'images')
    {
        return \Sys25\RnBase\Utility\TSFAL::fetchReferences($table, $uid, $refField);
    }

    /**
     * Get Extension name.
     *
     * @return string
     */
    public function getName()
    {
        return 't3twig_imageExtension';
    }
}
