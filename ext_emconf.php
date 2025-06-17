<?php

/**
 * Extension Manager/Repository config file for ext "t3twig".
 *
 * @category TYPO3-Extension
 *
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @see     https://www.dmk-ebusiness.de/
 */
$EM_CONF['t3twig'] = [
    'title' => 'T3twig',
    'description' => 'TYPO3 extension to render page templates with Twig and extend rn_base for using Twig templates instead of marker',
    'version' => '12.0.3',
    'state' => 'stable',
    'clearcacheonload' => 0,
    'category' => 'misc',
    'author' => 'DMK E-BUSINESS GmbH',
    'author_email' => 'dev@dmk-ebusiness.de',
    'author_company' => 'DMK E-BUSINESS GmbH',
    'constraints' => [
        'depends' => [
            'rn_base' => '1.18.0-',
            'typo3' => '11.5.0-12.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
