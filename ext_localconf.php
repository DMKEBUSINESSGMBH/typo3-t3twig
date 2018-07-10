<?php

/**
 * Ext_localconf
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['t3twig'] =
    'DMK\\T3twig\\Hook\\DataHandler->clearTwigCache';

$GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects'] = array_merge(
    $GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects'],
    [
        'TWIGTEMPLATE' => \DMK\T3twig\ContentObject\TwigContentObject::class,
    ]
);

if (!tx_rnbase_util_TYPO3::isTYPO76OrHigher()) {
    // Register ContentObject in 6.2
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['cObjTypeAndClass']['TWIGTEMPLATE'] = [
        'TWIGTEMPLATE',
        \DMK\T3twig\ContentObject\TwigContentObject::class
    ];
}
