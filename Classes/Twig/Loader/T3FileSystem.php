<?php

namespace DMK\T3twig\Twig\Loader;

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
 * Class TwigUtil
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig
 * @author   René Nitzsche
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class T3FileSystem extends \Twig_Loader_Filesystem
{
    private static $sysExtKeys = [
        'core', 'extbase', 'fluid', 'extensionmanager', 'lang', 'saltedpasswords',
       'backend', 'filelist', 'frontend', 'install', 'recordlist', 'sv', 't3skin', 'documentation',
       'info', 'info_pagetsconfig', 'setup', 'rtehtmlarea', 'rsaauth', 'func', 'wizard_crpages',
       'wizard_sortpages', 'about', 'aboutmodules', 'belog', 'beuser', 'context_help', 'cshmanual',
       'felogin', 'fluid_styled_content', 'form', 'impexp', 'lowlevel', 'reports', 'sys_note',
       't3editor', 'tstemplate', 'viewpage',
    ];

    public function __construct($paths = array(), $rootPath = null)
    {
        parent::__construct($paths, $rootPath);
        // Now add TYPO3 Extensions
        $this->addT3Namespaces();
    }
    /**
     * Add namespaces for TYPO3 extensions under EXT:extName/Resources/Private/
     * and fileadmin.
     * Template can be loaded with @EXT:extName/MyTemplate.html.twig
     */
    protected function addT3Namespaces()
    {
        $extKeys = array_filter(
            \tx_rnbase_util_Extensions::getLoadedExtensionListArray(),
            function ($v) {
                return !in_array($v, self::$sysExtKeys);
            }
        );
        foreach ($extKeys as $extKey) {
            $path = \tx_rnbase_util_Extensions::extPath($extKey);
            $path .= 'Resources/Private/';
            if (is_dir($path)) {
                $this->addPath($path, 'EXT:'.$extKey);
            }
        }
        // add fileadmin
        $path = PATH_site.'fileadmin/';
        if (is_dir($path)) {
            $this->addPath($path, 'fileadmin');
        }
    }
}
