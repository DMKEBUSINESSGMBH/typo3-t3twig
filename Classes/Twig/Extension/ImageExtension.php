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
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder;

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
    public function getFilters(): array
    {
        return [
        ];
    }

    public function getFunctions(): array
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
     * @return string
     */
    public function renderImage(
        EnvironmentTwig $env,
        mixed $image,
        array $arguments = [],
    ) {
        // get Resource Object (non ExtBase version), taken from Fluid\MediaViewHelper
        if (is_object($image) && is_callable([$image, 'getOriginalResource'])) {
            // We have a domain model, so we need to fetch the FAL resource object from there
            $image = $image->getOriginalResource();
        } else {
            $image = $env->getContentObject()->getImgResource(
                $image,
                $arguments
            );
        }

        if (!isset($image['processedFile']) && !isset($image['originalFile'])) {
            return '';
        }

        if (isset($image['processedFile'])) {
            $processedImg = $image['processedFile'];
            $image = $processedImg->getOriginalFile();
        } else {
            $image = $image['originalFile'];
            $cropString = $arguments['crop'];
            if (null === $cropString && $image->hasProperty('crop') && $image->getProperty('crop')) {
                $cropString = $image->getProperty('crop');
            }

            // CropVariantCollection needs a string, but this VH could also receive an array
            if (is_array($cropString)) {
                $cropString = json_encode($cropString);
            }

            $cropVariantCollection = CropVariantCollection::create((string) $cropString);
            $cropVariant = $arguments['cropVariant'] ?: 'default';
            $cropArea = $cropVariantCollection->getCropArea($cropVariant);
            $processingInstructions = [
                'width' => $arguments['width'] ?? '',
                'height' => $arguments['height'] ?? '',
                'minWidth' => $arguments['minWidth'] ?? ($arguments['minW'] ?? ''),
                'minHeight' => $arguments['minHeight'] ?? ($arguments['minH'] ?? ''),
                'maxWidth' => $arguments['maxWidth'] ?? ($arguments['maxW'] ?? ''),
                'maxHeight' => $arguments['maxHeight'] ?? ($arguments['maxH'] ?? ''),
                'crop' => $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($image),
            ];
            /** @var File $image */
            $processedImg = $image->process(ProcessedFile::CONTEXT_IMAGECROPSCALEMASK, $processingInstructions);
        }
        $tag = new TagBuilder('img');
        $tag->addAttribute('src', $processedImg->getPublicUrl());
        $tag->addAttribute('width', $processedImg->getProperty('width'));
        $tag->addAttribute('height', $processedImg->getProperty('height'));
        $tag->addAttribute('alt',
            $arguments['alt'] ?? ($arguments['altText'] ?? ($image->hasProperty('alternative') ? $image->getProperty('alternative') : ''))
        );
        $title = $arguments['title'] ?? ($arguments['titleText'] ?? ($image->hasProperty('title') ? $image->getProperty('title') : false));
        if ($title) {
            $tag->addAttribute('title', $title);
        }

        return $tag->render();
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
     */
    public function getName(): string
    {
        return 't3twig_imageExtension';
    }
}
