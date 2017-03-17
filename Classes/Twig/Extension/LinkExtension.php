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
 * Class LinkExtension
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class LinkExtension extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                't3linkOld',
                [$this, 'renderLinkOld'],
                ['needs_environment' => true, 'is_safe' => ['html']]
            ),
            new \Twig_SimpleFilter(
                't3link',
                [$this, 'renderLink'],
                ['needs_environment' => true, 'is_safe' => ['html']]
            ),
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                't3urlOld',
                [$this, 'renderUrlOld'],
                ['needs_environment' => true]
            ),
            new \Twig_SimpleFunction(
                't3url',
                [$this, 'renderUrl'],
                ['needs_environment' => true]
            ),
        ];
    }

    /**
     * @param EnvironmentTwig $env
     * @param                 label
     * @param                 $dest
     * @param array           $params
     * @param string          $tsPath
     *
     * @deprecated will be removed later
     *
     * @return string
     */
    public function renderLinkOld(EnvironmentTwig $env, $label, $dest, $params = [], $tsPath = 'link.')
    {
        \TYPO3\CMS\Core\Utility\GeneralUtility::logDeprecatedFunction();
        $arguments = [
            'destination' => $dest,
            'params'      => $params,
            'label'       => $label,
        ];

        if (is_array($tsPath)) {
            $arguments['config'] = $tsPath;
        } else {
            $arguments['tsPath'] = $tsPath;
        }

        $rnBaseLink = $this->makeRnbaseLink($env, $arguments);

        return $rnBaseLink->makeTag();
    }

    /**
     * @param EnvironmentTwig $env
     * @param                 label
     * @param array           $arguments
     *
     * @return string
     */
    public function renderLink(EnvironmentTwig $env, $label, array $arguments = [])
    {
        $arguments['label'] = $label;

        return $this->makeRnbaseLink($env, $arguments)->makeTag();
    }

    /**
     * @param EnvironmentTwig   $env
     * @param                   $dest
     * @param array             $params
     * @param string            $tsPath
     *
     * @deprecated will be removed later
     *
     * @return string
     */
    public function renderUrlOld(EnvironmentTwig $env, $dest, $params = [], $tsPath = 'link.')
    {
        \TYPO3\CMS\Core\Utility\GeneralUtility::logDeprecatedFunction();

        $arguments = [
            'destination' => $dest,
            'params'      => $params,
            'tsPath'      => $tsPath,
        ];

        if (is_array($tsPath)) {
            $arguments['config'] = $tsPath;
        } else {
            $arguments['tsPath'] = $tsPath;
        }

        $rnBaseLink = $this->makeRnbaseLink($env, $arguments);

        return $rnBaseLink->makeUrl(false);
    }

    /**
     * @param EnvironmentTwig $env
     * @param array           $arguments
     *
     * @return string
     */
    public function renderUrl(EnvironmentTwig $env, array $arguments = [])
    {
        return $this->makeRnbaseLink($env, $arguments)->makeUrl(false);
    }

    /**
     * Get Extension name
     *
     * @return string
     */
    public function getName()
    {
        return 't3twig_linkExtension';
    }

    /**
     * @param EnvironmentTwig $env
     * @param array           $arguments
     *
     * @return \tx_rnbase_util_Link
     */
    private function makeRnbaseLink(
        EnvironmentTwig $env,
        array $arguments = []
    ) {
        $arguments = $this->initiateArguments($arguments, $env);

        $params = $arguments->getParams() ? $arguments->getParams()->toArray() : [];
        $tsPath = $arguments->getTsPath();

        $primeval = $env->getConfigurations();
        //  this was recreated, if there are a overrule config
        $configurations = $primeval;
        $confId         = $env->getConfId();

        // we have additional configurations, merge them together in a new config object
        if ($arguments->hasConfig()) {
            $primeval = $env->getConfigurations();
            /** @var $configurations \Tx_Rnbase_Configuration_Processor */
            $configurations = \tx_rnbase::makeInstance(
                'Tx_Rnbase_Configuration_Processor'
            );
            $primevalConf   = $primeval->get($confId.$tsPath);
            if (is_array($primevalConf)) {
                $config = \tx_rnbase_util_Arrays::mergeRecursiveWithOverrule(
                    $primevalConf,
                    $arguments->getConfig()->toArray()
                );
            }
            $config = ['link.' => $config];
            $configurations->init(
                $config,
                $primeval->getCObj(),
                $primeval->getExtensionKey(),
                $primeval->getQualifier()
            );

            $confId = '';
            $tsPath = 'link.';
        }

        // reduce empty parameters
        if ($configurations->getBool($confId.$tsPath.'skipEmptyParams')) {
            foreach (array_keys($params, '') as $key) {
                unset($params[ $key ]);
            }
        }

        /// create link from original if overrule config exist (keep vars).
        $rnBaseLink = $primeval->createLink();
        $rnBaseLink->label($arguments->getLabel(), true);
        $rnBaseLink->initByTS($configurations, $confId.$tsPath, $params);
        // set destination only if set, so 0 for current page can be used
        if (!$arguments->isPropertyEmpty('destination')) {
            $rnBaseLink->destination($arguments->getDestination());
        }

        if (($extTarget = $configurations->get($confId.$tsPath.'extTarget'))) {
            $rnBaseLink->externalTargetAttribute($extTarget);
        }

        $this->shutdownArguments($arguments, $env);

        return $rnBaseLink;
    }
}
