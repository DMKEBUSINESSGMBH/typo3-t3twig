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
if (!defined('TYPO3_MODE')) {
    exit('Access denied.');
}

tx_rnbase_util_Extensions::addStaticFile('t3twig', 'Configuration/TypoScript/', 'T3Twig');
