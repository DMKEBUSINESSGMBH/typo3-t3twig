<?php

namespace t3twig;

/***************************************************************
 * Copyright notice
 *
 * (c) 2018 DMK E-BUSINESS GmbH <dev@dmk-ebusiness.de>
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
 * Special package class to enable install under TYPO3 6.2.
 *
 * @category TYPO3-Extension
 *
 * @author   René Nitzsche
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @see     https://www.dmk-ebusiness.de/
 */
class Package extends \TYPO3\CMS\Core\Package\Package
{
    /**
     * Check whether the given package requirement (like "typo3/flow" or "php") is a composer package or not.
     *
     * @param string $requirement the composer requirement string
     *
     * @return bool TRUE if $requirement is a composer package (contains a slash), FALSE otherwise
     */
    protected function packageRequirementIsComposerPackage($requirement)
    {
        if (!\tx_rnbase_util_TYPO3::isTYPO76OrHigher()) {
            // ignore composer dependencies starting with twig/
            if (1 === preg_match('/^twig\//', $requirement)) {
                return false;
            }
        }

        return parent::packageRequirementIsComposerPackage($requirement);
    }
}
