<?php

namespace DMK\T3twig\ContentObject;

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

use DMK\T3twig\Twig\RendererTwig as Renderer;
use DMK\T3twig\Twig\T3TwigException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\AbstractContentObject;

/**
 * Class DataHandler.
 *
 * @category TYPO3-Extension
 *
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @see     https://www.dmk-ebusiness.de/
 */
class TwigContentObject extends AbstractContentObject
{
    /**
     * @param string                $name
     * @param string                $typoscriptKey
     * @param ContentObjectRenderer $contentObject
     */
    public function cObjGetSingleExt($name, array $configuration, $typoscriptKey, ?\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $contentObject): string
    {
        $this->cObj = $contentObject;

        return $this->render($configuration);
    }

    /**
     * Rendering the cObject, TEMPLATE.
     *
     * @param array $conf Array of TypoScript properties
     *
     * @return string Output
     *
     * @see substituteMarkerArrayCached()
     *
     * @throws T3TwigException
     */
    public function render(
        $conf = [],
    ): string {
        $content = '';

        $configurations = $this->buildConfigurations($conf);
        $renderer = Renderer::instance(
            $configurations,
            ''
        );
        $contextData = $this->getContext($configurations);

        return $content.$renderer->render($contextData);
    }

    /**
     * Builds the configuration object based on the conf.
     *
     * @return \Sys25\RnBase\Configuration\Processor
     */
    private function buildConfigurations(
        array $conf,
    ) {
        /* @var $configurations \Sys25\RnBase\Configuration\ConfigurationInterface */
        $configurations = GeneralUtility::makeInstance(
            \Sys25\RnBase\Configuration\Processor::class
        );
        $configurations->init(
            $conf,
            $this->getContentObjectRenderer(),
            't3twig',
            't3twig'
        );

        return $configurations;
    }

    /**
     * Compile rendered content objects in variables array ready to assign to the view.
     *
     * @param \Sys25\RnBase\Configuration\Processor $configurations
     *
     * @return array the variables to be assigned
     *
     * @throws T3TwigException
     */
    protected function getContext($configurations): array
    {
        $contextData = [];
        $contextNames = $configurations->getKeyNames('context.');
        if (empty($contextNames)) {
            $contextNames = $configurations->getKeyNames('variables.');
        }

        $reservedVariables = ['data', 'current'];
        foreach ($contextNames as $key) {
            if (!in_array($key, $reservedVariables)) {
                $contextData[$key] = $configurations->get('context.'.$key, true);
            } else {
                throw new T3TwigException('Cannot use reserved name "'.$key.'" as variable name in TWIGTEMPLATE.', 1288095720);
            }
        }

        $contextData['data'] = $this->getContentObjectRenderer()->data;
        $contextData['current'] = $this->getContentObjectRenderer()->data[$this->getContentObjectRenderer()->currentValKey];

        return $contextData;
    }
}
