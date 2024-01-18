<?php

/**
 * Ext_localconf.
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
if (!isset($GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects'])) {
    $GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects'] = [];
}
$GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects'] = array_merge(
    $GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects'],
    [
        'TWIGTEMPLATE' => \DMK\T3twig\ContentObject\TwigContentObject::class,
    ]
);

if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['t3twig'])
    || !is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['t3twig'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['t3twig'] = [
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\PhpFrontend::class,
        'backend' => \TYPO3\CMS\Core\Cache\Backend\FileBackend::class,
        'groups' => ['system'],
    ];
}
