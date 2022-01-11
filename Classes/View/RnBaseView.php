<?php

namespace DMK\T3twig\View;

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
use Sys25\RnBase\Frontend\Request\RequestInterface;

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
class RnBaseView extends \Sys25\RnBase\Frontend\View\Marker\BaseView
{
    /**
     * @param $view
     * @param RequestInterface $request
     *
     * @return string
     *
     * @throws \DMK\T3twig\Twig\T3TwigException
     * @throws \TYPO3\CMS\Core\Exception
     * @throws \Throwable
     * @throws \Twig_Error_Runtime
     */
    public function render($view, RequestInterface $request)
    {
        $renderer = Renderer::instance(
            $request->getConfigurations(),
            $request->getConfId().'template.',
            // provide fallback template file (always a full filepath)
            $this->getTemplate($view, '.html.twig')
        );

        return $renderer->render(
            $request->getViewContext()->getArrayCopy()
        );
    }
}
