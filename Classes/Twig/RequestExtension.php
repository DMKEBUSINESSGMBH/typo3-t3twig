<?php
namespace DMK\T3twig\Twig;

/***************************************************************
 * Copyright notice
 *
 * (c) 2016 DMK E-BUSINESS GmbH <dev@dmk-ebusiness.de>
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

use DMK\T3twig\Util\T3twigEnvironment;

/**
 * Class TSParserExtension
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig\Twig
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class RequestExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                't3gp', [$this, 'renderGetPost'],
                ['needs_environment' => true]
            ),
        ];
    }

    /**
     * @param T3twigEnvironment $env
     * @param array             $record
     * @param string            $field
     *
     * @return string
     */
    public function renderGetPost(
        T3twigEnvironment $env,
        $paramName
    ) {
        $paths = explode('|', $paramName);
        $segment = array_shift($paths);

        $param = $env->getParameters()->get($segment);

        while (($segment = array_shift($paths)) !== null) {
            $param = $param[$segment];
        }

        return $param;
    }

    /**
     * Get Extension name
     *
     * @return string
     */
    public function getName()
    {
        return 't3twig_requestExtension';
    }
}
