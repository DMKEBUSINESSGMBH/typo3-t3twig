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
 * Class ImageExtension.
 *
 * @category TYPO3-Extension
 *
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @see     https://www.dmk-ebusiness.de/
 */
class AbstractExtension extends \Twig_Extension
{
    /**
     * Initiate the arguments,
     * sets the content object data
     * and performs the command.
     *
     * @param callable        $callable
     * @param EnvironmentTwig $env
     * @param array           $arguments
     *
     * @throws \Exception
     *
     * @return mixed
     */
    protected function performCommand(
        $callable,
        EnvironmentTwig $env = null,
        $arguments = null
    ) {
        $cObj = $env->getContentObject();
        $exception = null;

        // backup content object data
        $cObjData = $cObj->data;

        // initialize
        $arguments = $this->initiateArguments($arguments, $env);

        try {
            // perform command
            $return = call_user_func($callable, $arguments);
        } catch (\Exception $exception) {
            // the exception is thrown after the shutdown
        }

        // restore content object data
        $cObj->data = $cObjData;
        $cObj->setCurrentVal(false);

        // throw exception, if command thows one
        if ($exception instanceof \Exception) {
            throw $exception;
        }

        return $return;
    }

    /**
     * Creates a new data instance.
     *
     * @param array|\Sys25\RnBase\Domain\Model\DataModel $arguments
     * @param EnvironmentTwig                    $env
     *
     * @return \Sys25\RnBase\Domain\Model\DataModel
     */
    protected function initiateArguments(
        $arguments = null,
        EnvironmentTwig $env = null
    ) {
        $arguments = \Sys25\RnBase\Domain\Model\DataModel::getInstance($arguments);

        if ($env instanceof EnvironmentTwig) {
            $this->setContentObjectData(
                $env,
                $arguments->getData(),
                $arguments->getCurrentValue()
            );
        }

        // convert ts config from  array to ts array
        if ($arguments->hasTsConfig()) {
            $arguments->setTsConfig(
                \Sys25\RnBase\Utility\TypoScript::convertPlainArrayToTypoScriptArray(
                    $arguments->getTsConfig()->toArray()
                )
            );
        }

        return $arguments;
    }

    /**
     * Sets the data in the current content object and backups the current value.
     *
     * @param EnvironmentTwig $env
     * @param array           $data
     * @param string          $currentValue
     */
    protected function setContentObjectData(
        EnvironmentTwig $env,
        $data = null,
        $currentValue = null
    ) {
        $contentObject = $env->getContentObject();

        if (null === $data) {
            // nothing todo, if there are no data to set
        } elseif (is_scalar($data)) {
            $currentValue = $currentValue ?: (string) $data;
            $data = [$data];
        } elseif ($data instanceof \Sys25\RnBase\Domain\Model\DataModel) {
            $data = $data->toArray();
        }

        // set data
        if (null !== $data) {
            $contentObject->data = $data;
        }

        if (null !== $currentValue) {
            $contentObject->setCurrentVal($currentValue);
        }
    }
}
