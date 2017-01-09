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

use DMK\T3twig\Util\TwigUtil;

/**
 * Class BaseTwigView
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig\View
 * @author   Eric Hertwig
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class BaseTwigView extends \tx_rnbase_view_Base
{
    /**
     * @param string                    $view
     * @param \tx_rnbase_configurations $configurations
     *
     * @return string
     */
    public function render($view, &$configurations)
    {
        $templateFullFilePath = \tx_rnbase_util_Files::getFileAbsFileName(
            $this->getTemplate($view, '.html.twig')
        );

        $twigLoader = TwigUtil::getTwigLoaderFilesystem(
            dirname($templateFullFilePath)
        );
        TwigUtil::injectTemplatePaths(
            $twigLoader,
            $configurations->getExploded('twig_templatepaths.')
        );

        $twigEnv = TwigUtil::getTwigEnvironment($twigLoader);
        $twigEnv->setView($this);
        TwigUtil::injectExtensions(
            $twigEnv,
            $configurations->getExploded('twig_extensions.')
        );

        $template = $twigEnv->loadTemplate(
            basename($templateFullFilePath)
        );
        $result = $template->render(
            $configurations->getViewData()->getArrayCopy()
        );

        return $result;
    }
}
