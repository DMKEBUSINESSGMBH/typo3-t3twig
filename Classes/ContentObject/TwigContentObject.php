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
     */
    public function render(
        $conf = []
    ) {
        $content = '';

        $renderer = Renderer::instance(
            $this->buildConfigurations($conf),
            '',
            $conf
        );

        $content .= $renderer->render(
            array_merge(
                (array) $this->getContentObject()->data,
                $conf
            )
        );

        return $content;
    }

    /**
     * Builds the  configuration object based on the conf
     *
     * @param array $conf
     *
     * @return \tx_rnbase_configurations
     */
    private function buildConfigurations(
        array $conf
    ) {
        /* @var $configurations \tx_rnbase_configurations */
        $configurations = \tx_rnbase::makeInstance(
            'tx_rnbase_configurations'
        );
        $configurations->init(
            $conf,
            $this->getContentObject(),
            't3twig',
            't3twig'
        );

        return $configurations;
    }
}
