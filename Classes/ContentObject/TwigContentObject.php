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

use \TYPO3\CMS\Frontend\ContentObject\AbstractContentObject;
use DMK\T3twig\Twig\RendererTwig as Renderer;
use DMK\T3twig\Twig\T3TwigException;

/**
 * Class DataHandler
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class TwigContentObject extends AbstractContentObject
{
    /**
     * Rendering the cObject, TEMPLATE
     *
     * @param array $conf Array of TypoScript properties
     * @return string Output
     * @see substituteMarkerArrayCached()
     * @throws T3TwigException
     */
    public function render(
        $conf = []
    ) {
        $content = '';

        $configurations = $this->buildConfigurations($conf);
        $renderer = Renderer::instance(
            $configurations,
            '',
            $conf
        );
        $contextData = $this->getContext($configurations);

        $content .= $renderer->render($contextData);

        return $content;
    }

    /**
     * Builds the  configuration object based on the conf
     *
     * @param array $conf
     *
     * @return \Tx_Rnbase_Configuration_Processor
     */
    private function buildConfigurations(
        array $conf
    ) {
        /* @var $configurations \Tx_Rnbase_Configuration_ProcessorInterface */
        $configurations = \tx_rnbase::makeInstance(
            'Tx_Rnbase_Configuration_Processor'
        );
        $configurations->init(
            $conf,
            $this->getContentObject(),
            't3twig',
            't3twig'
        );

        return $configurations;
    }

    /**
     * Compile rendered content objects in variables array ready to assign to the view
     *
     * @param \Tx_Rnbase_Configuration_Processor $configurations
     * @return array the variables to be assigned
     * @throws T3TwigException
     */
    protected function getContext($configurations)
    {
        $contextData = [];
        $contextNames = $configurations->getKeyNames('context.');
        if($contextNames === NULL) {
            // compat with limited features
            $contextData = array_merge(
                (array) $this->getContentObject()->data,
                $conf
            );
        }
        else {
            $reservedVariables = ['data', 'current', 'page'];
            foreach ($contextNames As $key) {
                if (!in_array($variableName, $reservedVariables)) {
                    $contextData[$key] = $configurations->get('context.'.$key, true);
                } else {
                    throw new T3TwigException(
                        'Cannot use reserved name "' . $variableName . '" as variable name in TWIGTEMPLATE.',
                        1288095720
                    );
                }
            }

            $contextData['data'] = $this->getContentObject()->data;
            $contextData['page'] = \tx_rnbase_util_TYPO3::getTSFE()->page;
            $contextData['current'] = $this->getContentObject()->data[$this->getContentObject()->currentValKey];
        }

        return $contextData;
    }
}
