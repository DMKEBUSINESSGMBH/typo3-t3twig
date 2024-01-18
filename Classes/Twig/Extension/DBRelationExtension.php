<?php

namespace DMK\T3twig\Twig\Extension;

/*
 * *************************************************************
 * Copyright notice
 *
 * (c) 2019 DMK E-BUSINESS GmbH <dev@dmk-ebusiness.de>
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 * *************************************************************
 */
use DMK\T3twig\Twig\EnvironmentTwig;
use Twig\TwigFunction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class TSParserExtension.
 *
 * @category TYPO3-Extension
 *
 * @author René Nitzsche
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @see https://www.dmk-ebusiness.de/
 */
class DBRelationExtension extends \Twig\Extension\AbstractExtension
{
    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('t3dbrel', [
                $this,
                'lookupRelation',
            ], [
                'needs_environment' => true,
            ]),
        ];
    }

    /**
     * @param \DMK\T3twig\Twig\EnvironmentTwig $env
     * @param string                           $paramName
     * @param array                            $arguments
     *
     * @return mixed|null
     */
    public function lookupRelation(
        EnvironmentTwig $env,
        \Sys25\RnBase\Domain\Model\BaseModel $entity,
        array $arguments = []
    ) {
        $confId = sprintf('%srelations.%s.', $env->getConfId(), htmlspecialchars($arguments['relation']));

        $alias = $env->getConfigurations()->get($confId.'join.alias');
        $field = $env->getConfigurations()->get($confId.'join.field');
        if (!$alias || !$field) {
            throw new \Exception(sprintf("Verify config for relation '%s' Table alias or field not found. Full typoscript path: %s", htmlspecialchars($arguments['relation']), $confId));
        }

        $fields = $options = [];
        $fields[$alias.'.'.$field][OP_EQ_INT] = $entity->getUid();

        \Sys25\RnBase\Search\SearchBase::setConfigFields($fields, $env->getConfigurations(), $confId.'fields.');
        \Sys25\RnBase\Search\SearchBase::setConfigOptions($options, $env->getConfigurations(), $confId.'options.');

        if ($otherOptions = isset($arguments['options']) ? $arguments['options'] : []) {
            $options = \Sys25\RnBase\Utility\Arrays::mergeRecursiveWithOverrule($options, $otherOptions);
        }

        if ($otherFields = isset($arguments['fields']) ? $arguments['fields'] : []) {
            $fields = \Sys25\RnBase\Utility\Arrays::mergeRecursiveWithOverrule($fields, $otherFields);
        }

        $searcher = GeneralUtility::makeInstance($env->getConfigurations()->get($confId.'callback.class'));
        $method = $env->getConfigurations()->get($confId.'callback.method');

        return $searcher->$method($fields, $options);
    }

    /**
     * Get Extension name.
     *
     * @return string
     */
    public function getName()
    {
        return 't3twig_dbrelationExtension';
    }
}
