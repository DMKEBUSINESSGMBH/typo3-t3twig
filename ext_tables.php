<?php

/**
 * Ext_tables.
 *
 * @category TYPO3-Extension
 *
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @see     https://www.dmk-ebusiness.de/
 */
if (!defined('TYPO3')) {
    exit('Access denied.');
}

\Sys25\RnBase\Utility\Extensions::addStaticFile('t3twig', 'Configuration/TypoScript/', 'T3Twig');
