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

/**
 * Class ImageExtension
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @author   Mario Seidel <dev@dmk-ebusiness.de>
 * @author   Michael Wagner
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
                't3images',
                [$this, 'renderImages'],
                ['needs_environment' => true]
            ),
            new \Twig_SimpleFilter(
                't3imageFromTS',
                [$this, 'renderImageFromTyposcript'],
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
            new \Twig_SimpleFunction(
                't3genericImages',
                [$this, 'renderGenericImage'],
                ['needs_environment' => true, 'is_safe' => ['html']]
            ),
            new \Twig_SimpleFunction(
                't3image',
                [$this, 'renderImage'],
                ['needs_environment' => true, 'is_safe' => ['html']]
            ),
        ];
    }

    /**
     * @param EnvironmentTwig                         $env
     * @param \Tx_Rnbase_Domain_Model_DomainInterface $model
     * @param string                                  $refField
     *
     * @return array
     */
    public function renderImages(
        EnvironmentTwig $env,
        \Tx_Rnbase_Domain_Model_DomainInterface $model,
        $refField = 'images'
    ) {
        $fileRefs = \tx_rnbase_util_TSFAL::fetchReferences($model->getTableName(), $model->getUid(), $refField);

        return $this->renderReferences($env, $fileRefs, $refField);
    }

    /**
     * @param EnvironmentTwig $env
     * @param                 $image
     * @param array           $options
     *
     * @return array
     */
    public function renderImage(
        EnvironmentTwig $env,
        $image,
        $options = []
    ) {
        $configurations = $env->getConfigurations();

        // get Resource Object (non ExtBase version), taken from Fluid\MediaViewHelper
        if (is_object($image) && is_callable([$image, 'getOriginalResource'])) {
            // We have a domain model, so we need to fetch the FAL resource object from there
            $image = $image->getOriginalResource();
        }

        return $configurations->getCObj()->cImage(
            $image,
            $options
        );
    }

    /**
     * @param EnvironmentTwig   $env
     * @param                   $table
     * @param                   $uid
     * @param string            $refField
     * @param string            $tsPathConfig
     *
     * @return array
     */
    public function renderGenericImage(
        EnvironmentTwig $env,
        $table,
        $uid,
        $refField = 'images',
        $tsPathConfig = 'images'
    ) {
        $fileRefs = \tx_rnbase_util_TSFAL::fetchReferences($table, $uid, $refField);

        return $this->renderReferences($env, $fileRefs, $tsPathConfig);
    }


    /**
     * @param EnvironmentTwig $env
     * @param string          $tsPath
     *
     * @return string
     */
    public function renderImageFromTyposcript(EnvironmentTwig $env, $tsPath)
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
     * @param EnvironmentTwig   $env
     * @param                   $fileRefs
     * @param                   $tsPathConfig
     *
     * @return array
     */
    protected function renderReferences($env, $fileRefs, $tsPathConfig)
    {
        $configurations = $env->getConfigurations();
        $confId         = $env->getConfId();
        $images         = [];
        $cObj           = $configurations->getCObj();

        foreach ($fileRefs as $fileRef) {
            $cObj->setCurrentFile($fileRef);
            $images[] = $cObj->cImage(
                $fileRef,
                $configurations->getExploded($confId.$tsPathConfig.'.')
            );
        }

        return $images;
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
