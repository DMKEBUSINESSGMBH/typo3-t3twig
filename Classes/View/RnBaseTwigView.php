<?php

namespace DMK\T3twig\View;

/***************************************************************
 * Copyright notice
 *
 * (c) 2016-2019 DMK E-BUSINESS GmbH <dev@dmk-ebusiness.de>
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
use Sys25\RnBase\Frontend\Request\RequestInterface;
use Sys25\RnBase\Frontend\View\AbstractView;
use Sys25\RnBase\Frontend\View\ViewInterface;

/**
 * Class BaseTwigView.
 *
 * @category TYPO3-Extension
 *
 * @author   Eric Hertwig
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @see     https://www.dmk-ebusiness.de/
 */
class RnBaseTwigView extends AbstractView implements ViewInterface
{
    /**
     * @param string                                      $view
     * @param \Tx_Rnbase_Configuration_ProcessorInterface $configurations
     *
     * @return string
     */
    public function render($view, RequestInterface $request)
    {
        $configurations = $request->getConfigurations();
        $renderer = Renderer::instance(
            $configurations,
            $request->getConfId().'template.',
            // provide fallback template file (always a full filepath)
            $this->getTemplate($view, '.html.twig')
        );

        return $renderer->render(
            $request->getViewContext()->getArrayCopy()
        );
    }

    /**
     * Set the path of the template directory.
     *
     * You can make use the syntax EXT:myextension/somepath.
     * It will be evaluated to the absolute path by tx_rnbase_util_Files::getFileAbsFileName()
     *
     * @param string path to the directory containing the php templates
     */
    public function setTemplatePath($pathToTemplates)
    {
        $this->pathToTemplates = $pathToTemplates;
    }
}
