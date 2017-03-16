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

use DMK\T3twig\Twig\EnvironmentTwig;

/**
 * Class TSParserExtension
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class TSParserExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                't3parseField',
                [$this, 'parseField'],
                ['needs_environment' => true, 'is_safe' => ['html'],]
            ),
        ];
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                't3cObject',
                [$this, 'renderContentObject'],
                ['needs_environment' => true, 'is_safe' => ['html']]
            ),
        ];
    }

    /**
     * @param EnvironmentTwig $env
     * @param array           $record
     * @param string          $field
     *
     * @return string
     */
    public function parseField(EnvironmentTwig $env, $record, $field)
    {
        $configurations = $env->getConfigurations();
        $confId         = $env->getConfId();
        $conf           = $configurations->get($confId);
        $cObj           = $configurations->getCObj();
        $tmp            = $cObj->data;
        $value          = $record[ $field ];

        $cObj->data = $record;

        // For DATETIME there is a special treatment to treat empty values
        if ($conf[ $field ] == 'DATETIME' && $conf[ $field.'.' ]['ifEmpty'] && !$value) {
            $value = $conf[ $field.'.' ]['ifEmpty'];
        } elseif ($conf[ $field ]) {
            $cObj->setCurrentVal($value);
            $value = $cObj->cObjGetSingle($conf[ $field ], $conf[ $field.'.' ]);
            $cObj->setCurrentVal(false);
        } elseif ($conf[ $field ] == 'CASE') {
            $value = $cObj->CASEFUNC($conf[ $field.'.' ]);
        } else {
            $value = $cObj->stdWrap($value, $conf[ $field.'.' ]);
        }

        $cObj->data = $tmp;

        return $value;
    }

    /**
     * Creates output based on TypoScript.
     *
     * @param EnvironmentTwig $env
     * @param string          $typoscriptObjectPath
     * @param array           $data
     *
     * @throws \Exception
     *
     * @return string
     */
    public function renderContentObject(
        EnvironmentTwig $env,
        $typoscriptObjectPath,
        $data = null
    ) {
        $currentValue = null;
        if (is_scalar($data)) {
            $currentValue = (string)$data;
            $data = [$data];
        }
        // @TODO: handle objects!

        $configurations = $env->getConfigurations();
        $contentObject = $configurations->getCObj();

        // set data
        if ($data !== null) {
            $backupData = $contentObject->data;
            $contentObject->data = $data;
        }

        if ($currentValue !== null) {
            $contentObject->setCurrentVal($currentValue);
        }

        $setup = \tx_rnbase_util_TYPO3::getTSFE()->tmpl->setup;

        $pathSegments = \Tx_Rnbase_Utility_Strings::trimExplode(
            '.',
            $typoscriptObjectPath
        );
        $lastSegment  = array_pop($pathSegments);

        // check the ts path and find the setup config
        foreach ($pathSegments as $segment) {
            if (!array_key_exists(($segment . '.'), $setup)) {
                $setup = false;
                break;
            }
            $setup = $setup[$segment . '.'];
        }

        // try to get value from configuration directly, if no global ts was found
        if ($setup === false) {
            $setup = $configurations->get(
                $env->getConfId() . implode('.', $pathSegments) . '.'
            );
        }

        // no config found?
        if (!is_array($setup)) {
            throw new \Exception(
                sprintf(
                    'Global TypoScript object path "%s" and plugin context configuration "%s" does not exist',
                    htmlspecialchars($typoscriptObjectPath),
                    htmlspecialchars($env->getConfId() . $typoscriptObjectPath)
                ),
                1483710972
            );
        }

        // render the ts
        $content = $contentObject->cObjGetSingle(
            $setup[$lastSegment],
            $setup[$lastSegment . '.']
        );

        // reset data
        if (isset($backupData)) {
            $contentObject->data = $backupData;
        }

        return $content;
    }

    /**
     * Get Extension name
     *
     * @return string
     */
    public function getName()
    {
        return 't3twig_tsParserExtension';
    }
}
