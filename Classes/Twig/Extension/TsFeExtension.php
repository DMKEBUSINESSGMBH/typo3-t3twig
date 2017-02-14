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

/**
 * Class TSParserExtension
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class TsFeExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'tsfePagetitle',
                [$this, 'setPageTitleTag'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    /**
     * {@inheritDoc}
     * @see Twig_Extension::getGlobals()
     */
    public function getGlobals()
    {
        return [
            'tsfe' => $GLOBALS['TSFE'],
        ];
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function setPageTitleTag($value)
    {
        if (empty($value)) {
            return '';
        }

        $GLOBALS['TSFE']->altPageTitle = $value;
        $GLOBALS['TSFE']->indexedDocTitle = $value;

        return sprintf(
            '<!-- page title set to "%s" by twig function "tsfePagetitle". -->',
            $value
        );
    }

    /**
     * Get Extension name
     *
     * @return string
     */
    public function getName()
    {
        return 't3twig_tsFeExtension';
    }
}
