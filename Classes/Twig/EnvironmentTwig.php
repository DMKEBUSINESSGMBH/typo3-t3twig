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

use DMK\T3twig\Twig\RendererTwig as Renderer;

/**
 * Class EnvironmentTwig
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class EnvironmentTwig extends \Twig_Environment
{
    /**
     * @var Renderer
     */
    protected $renderer;

    /**
     * Sets the current renderer
     *
     * @param \DMK\T3twig\Twig\RendererTwig $renderer
     *
     * @return $this
     */
    public function setRenderer(
        Renderer $renderer
    ) {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * The current configurations
     *
     * @return \Tx_Rnbase_Configuration_ProcessorInterface
     */
    public function getConfigurations()
    {
        return $this->renderer->getConfigurations();
    }

    /**
     * The current configurations object
     *
     * @return \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
     */
    public function getContentObject()
    {
        return $this->getConfigurations()->getContentObject();
    }

    /**
     * The current confId
     *
     * @return string
     */
    public function getConfId()
    {
        return $this->renderer->getConfId();
    }

    /**
     * @return \tx_rnbase_parameters
     */
    public function getParameters()
    {
        return $this->getConfigurations()->getParameters();
    }
}
