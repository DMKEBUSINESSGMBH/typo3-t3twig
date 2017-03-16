<?php

/**
 * Extension Manager/Repository config file for ext "t3twig".
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */

$EM_CONF[ $_EXTKEY ] = [
    'title'                         => 'T3twig',
    'description'                   => 'TYPO3 extension to render page templates with Twig and extend rn_base for using Twig templates instead of marker ',
    'shy'                           => 0,
    'version'                       => '1.1.0',
    'dependencies'                  => 'cms',
    'conflicts'                     => '',
    'priority'                      => '',
    'loadOrder'                     => '',
    'module'                        => '',
    'state'                         => 'stable',
    'uploadfolder'                  => 0,
    'modify_tables'                 => '',
    'clearcacheonload'              => 0,
    'lockType'                      => '',
    'category'                      => 'misc',
    'author'                        => 'DMK E-BUSINESS GmbH',
    'author_email'                  => 'dev@dmk-ebusiness.de',
    'author_company'                => 'DMK E-BUSINESS GmbH',
    'CGLcompliance'                 => '',
    'CGLcompliance_note'            => '',
    'constraints'                   => [
        'depends'   => [
            'rn_base' => '1.2.5-',
            'typo3'   => '7.6.16-7.6.99',
        ],
        'conflicts' => [],
        'suggests'  => [],
    ],
    '_md5_values_when_last_written' => '',
    'suggests'                      => [
    ],
    'createDirs'                    => 'typo3temp/t3twig/',
];
