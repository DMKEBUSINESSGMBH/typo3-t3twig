<?php

/**
 * Extension Manager/Repository config file for ext "t3twig".
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link	 https://www.dmk-ebusiness.de/
 */

$EM_CONF[$_EXTKEY] = array(
	'title' => 'T3twig',
	'description' => 'Extend rn_base for using twig templates instead of marker templates',
	'shy' => 0,
	'version' => '0.0.1',
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'category' => 'misc',
	'author' => 'DMK E-BUSINESS GmbH',
	'author_email' => 'dev@dmk-ebusiness.de',
	'author_company' => 'DMK E-BUSINESS GmbH',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array (
		'depends' => array (
			'rn_base' => '1.0.4-',
			'typo3' => '6.2.22-7.6.99',
		),
		'conflicts' => array (),
		'suggests' => array ()
	),
	'_md5_values_when_last_written' => '',
	'suggests' => array(
	),
);
