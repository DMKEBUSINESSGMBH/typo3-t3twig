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
 * Class DateExtension.
 *
 * @category TYPO3-Extension
 *
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @see     https://www.dmk-ebusiness.de/
 */
class DateExtension extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                't3strftime',
                [$this, 'renderStrfTime'],
                ['needs_environment' => true, 'is_safe' => ['html']]
            ),
        ];
    }

    /**
     * Formats a date with strftime.
     *
     * @param EnvironmentTwig         $env
     * @param string|int|DateInterval $date
     * @param string                  $format
     * @param array                   $arguments
     *
     * @return string
     */
    public function renderStrfTime(
        EnvironmentTwig $env,
        $date,
        $format = null,
        array $arguments = []
    ) {
        if (null === $format) {
            $formats = $env->getExtension('Twig_Extension_Core')->getDateFormat();
            $format = $date instanceof DateInterval ? $formats[1] : $formats[0];
        }

        if (!$date instanceof DateInterval) {
            $date = twig_date_converter($env, $date);
        }

        $date = $date->format('U');

        // some conversion for windows systems
        if (TYPO3_OS === 'WIN') {
            $mapping = [
                '%C' => sprintf('%02d', date('Y', $date) / 100),
                '%D' => '%m/%d/%y',
                '%e' => sprintf("%' 2d", date('j', $date)),
                '%G' => '%Y',
                '%h' => '%b',
                '%n' => "\n",
                '%r' => date('h:i:s', $date).' %p',
                '%R' => date('H:i', $date),
                '%t' => "\t",
                '%T' => '%H:%M:%S',
                '%u' => ($w = date('w', $date)) ? $w : 7,
            ];
            $format = str_replace(
                array_keys($mapping),
                array_values($mapping),
                $format
            );
        }

        return $this->performCommand(
            function () use ($format, $date) {
                return strftime($format, $date);
            },
            $env,
            $arguments
        );
    }

    /**
     * Get Extension name.
     *
     * @return string
     */
    public function getName()
    {
        return 't3twig_dateExtension';
    }
}
