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
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class TSParserExtension.
 *
 * @category TYPO3-Extension
 *
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @see     https://www.dmk-ebusiness.de/
 */
class TSParserExtension extends AbstractExtension
{
    /**
     * Twig Filters.
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter(
                't3ts',
                [$this, 'applyTs'],
                ['needs_environment' => true, 'is_safe' => ['html']]
            ),
            new TwigFilter(
                't3rte',
                [$this, 'applyRte'],
                ['needs_environment' => true, 'is_safe' => ['html']]
            ),
        ];
    }

    /**
     * Twig Functions.
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                't3cObject',
                [$this, 'renderContentObject'],
                ['needs_environment' => true, 'is_safe' => ['html']]
            ),
            new TwigFunction(
                't3stdWrap',
                [$this, 'renderStdWrap'],
                ['needs_environment' => true, 'is_safe' => ['html']]
            ),
            new TwigFunction(
                't3tsRaw',
                [$this, 'renderTsRaw'],
                ['needs_environment' => true, 'is_safe' => ['html']]
            ),
            new TwigFunction(
                't3parseFunc',
                [$this, 'renderParseFunc'],
                ['needs_environment' => true, 'is_safe' => ['html']]
            ),
        ];
    }

    /**
     * Creates output based on TypoScript.
     *
     * @param string          $value
     * @param string          $confId
     *
     * @return string
     */
    public function applyTs(
        EnvironmentTwig $env,
        $value,
        $confId,
        array $arguments = [],
    ) {
        // set the current value to arguments for initialize, if not set
        if (!isset($arguments['current_value'])) {
            $arguments['current_value'] = $value;
        }

        return $this->performCommand(
            function (\Sys25\RnBase\Domain\Model\DataModel $arguments) use ($env, $value, $confId) {
                // dont throw exception, if ts path does not exists
                $arguments->setSkipTsNotFoundException(true);

                [$tsPath, $setup] = $this->findSetup($env, $confId, $arguments);

                $conf = empty($setup[$tsPath.'.']) ? [] : $setup[$tsPath.'.'];

                if (!isset($setup[$tsPath])) {
                    return $env->getContentObject()->stdWrap($value, $conf);
                }

                return $env->getContentObject()->cObjGetSingle($setup[$tsPath], $conf);
            },
            $env,
            $arguments
        );
    }

    /**
     * Creates output based on parseFunc_RTE.
     *
     * @param string          $value
     * @param string          $confId
     *
     * @return string
     */
    public function applyRte(
        EnvironmentTwig $env,
        $value,
        $confId = 'lib.parseFunc_RTE',
        array $arguments = [],
    ) {
        $arguments['current_value'] = $value;

        return $this->renderParseFunc($env, $confId, $arguments);
    }

    /**
     * Creates output based on TypoScript parseFunc.
     *
     * @param string          $confId
     *
     * @return string
     */
    public function renderParseFunc(
        EnvironmentTwig $env,
        $confId,
        array $arguments = [],
    ) {
        return $this->performCommand(
            function (\Sys25\RnBase\Domain\Model\DataModel $arguments) use ($env, $confId) {
                [$tsPath, $setup] = $this->findSetup($env, $confId, $arguments);
                $conf = empty($setup[$tsPath.'.']) ? [] : $setup[$tsPath.'.'];

                return $env->getContentObject()->parseFunc($arguments->getCurrentValue(), $conf);
            },
            $env,
            $arguments
        );
    }

    /**
     * Creates output based on TypoScript.
     *
     * @param string          $confId
     *
     * @return string
     *
     * @throws \Exception
     */
    public function renderContentObject(
        EnvironmentTwig $env,
        $confId,
        array $arguments = [],
    ) {
        return $this->performCommand(
            function (\Sys25\RnBase\Domain\Model\DataModel $arguments) use ($env, $confId) {
                [$tsPath, $setup] = $this->findSetup($env, $confId, $arguments);

                return $env->getContentObject()->cObjGetSingle(
                    $setup[$tsPath],
                    $setup[$tsPath.'.'] ?? []
                );
            },
            $env,
            $arguments
        );
    }

    /**
     * Creates output based on TypoScript.
     *
     * @param string          $confId
     *
     * @return string
     *
     * @throws \Exception
     */
    public function renderStdWrap(
        EnvironmentTwig $env,
        $confId,
        array $arguments = [],
    ) {
        return $this->performCommand(
            function (\Sys25\RnBase\Domain\Model\DataModel $arguments) use ($env, $confId) {
                [$tsPath, $setup] = $this->findSetup($env, $confId, $arguments);

                return $env->getContentObject()->stdWrap(
                    $setup[$tsPath] ?? '',
                    $setup[$tsPath.'.'] ?? []
                );
            },
            $env,
            $arguments
        );
    }

    /**
     * Creates output based on TypoScript.
     *
     * @param string          $confId
     *
     * @return string
     *
     * @throws \Exception
     */
    public function renderTsRaw(
        EnvironmentTwig $env,
        $confId,
        array $arguments = [],
    ) {
        return $this->performCommand(
            function (\Sys25\RnBase\Domain\Model\DataModel $arguments) use ($env, $confId) {
                [$tsPath, $setup] = $this->findSetup($env, $confId, $arguments);

                if (empty($confId) && $arguments->hasTsPath()) {
                    $confId = $arguments->getTsPath();
                }

                if (str_ends_with($confId, '.')) {
                    return $setup;
                }

                return $setup[$tsPath];
            },
            $env,
            $arguments
        );
    }

    /**
     * Try to wind the setup of the given conf id.
     *
     * @param string                       $typoscriptObjectPath
     *
     * @throws \Exception
     */
    protected function findSetup(
        EnvironmentTwig $env,
        $typoscriptObjectPath,
        \Sys25\RnBase\Domain\Model\DataModel $arguments,
    ): array {
        if (empty($typoscriptObjectPath) && $arguments->hasTsPath()) {
            $typoscriptObjectPath = $arguments->getTsPath();
        }

        if (empty($typoscriptObjectPath)) {
            throw new \Exception('No TypoScript path given. arguments = {"ts_path" : "lib.testlink"}', 1489658526);
        }

        $setup = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.typoscript')->getSetupArray();

        $pathSegments = \Sys25\RnBase\Utility\Strings::trimExplode(
            '.',
            $typoscriptObjectPath
        );
        $lastSegment = array_pop($pathSegments);

        // check the ts path and find the setup config
        foreach ($pathSegments as $segment) {
            if (!array_key_exists($segment.'.', $setup)) {
                $setup = false;
                break;
            }

            $setup = $setup[$segment.'.'];
        }

        // try to get value from configuration directly, if no global ts was found
        if ([] === $pathSegments || false === $setup) {
            $setup = $env->getConfigurations()->get(
                $env->getConfId().'ts.'.([] === $pathSegments ? '' : implode('.', $pathSegments).'.')
            );
        }

        // no config found?
        if (!$arguments->hasSkipTsNotFoundException() && !is_array($setup)) {
            throw new \Exception(sprintf('Global TypoScript object path "%s" or plugin context configuration "%s" does not exist', htmlspecialchars($typoscriptObjectPath), htmlspecialchars($env->getConfId().'ts.'.$typoscriptObjectPath)), 1483710972);
        }

        return [$lastSegment, $setup];
    }

    /**
     * Get Extension name.
     */
    public function getName(): string
    {
        return 't3twig_tsParserExtension';
    }
}
