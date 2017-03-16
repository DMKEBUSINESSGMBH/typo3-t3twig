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
 * Class ImageExtension
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class AbstractExtension extends \Twig_Extension
{
    private $contentObjectDataBackup = null;

    /**
     * Creates a new data instance.
     *
     * @param array|\Tx_Rnbase_Domain_Model_Data $arguments
     * @param EnvironmentTwig $env
     *
     * @return \Tx_Rnbase_Domain_Model_Data
     */
    protected function initiateArguments(
        $arguments = null,
        EnvironmentTwig $env = null
    ) {
       $arguments = \Tx_Rnbase_Domain_Model_Data::getInstance($arguments);

       if ($env instanceof EnvironmentTwig &&  !$arguments->isPropertyEmpty('data')) {
           $this->setContentObjectData($env, $arguments->getData()->toArray());
       }

       return $arguments;
    }

    /**
     * Creates a new data instance.
     *
     * @param array|\Tx_Rnbase_Domain_Model_Data $arguments
     * @param EnvironmentTwig $env
     *
     * @return \Tx_Rnbase_Domain_Model_Data
     */
    protected function shutdownArguments(
        $arguments = null,
        EnvironmentTwig $env = null
    ) {
       $arguments = \Tx_Rnbase_Domain_Model_Data::getInstance($arguments);

       if ($env instanceof EnvironmentTwig &&  !$arguments->isPropertyEmpty('data')) {
           $this->restoreContentObjectData($env);
       }

       return $arguments;
    }

    /**
     * Sets the data in the current content object and backups the current value.
     *
     * @param EnvironmentTwig $env
     * @param array $data
     * @param string $currentValue
     *
     * @return void
     */
    protected function setContentObjectData(
        EnvironmentTwig $env,
        $data,
        $currentValue = null
    ) {
        $contentObject = $env->getContentObject();

        if (is_scalar($data)) {
            $currentValue = $currentValue ?: (string) $data;
            $data = [$data];
        }
        // @TODO: handle objects!

        // set data
        if ($data !== null) {
            if ($this->contentObjectDataBackup === null) {
                $this->contentObjectDataBackup = $contentObject->data;
            }
            $contentObject->data = $data;
            if ($currentValue !== null) {
                $contentObject->setCurrentVal($currentValue);
            }
        }
    }

    /**
     * Restores the content object data from the backup
     *
     * @param EnvironmentTwig $env
     *
     * @return array
     */
    protected function restoreContentObjectData(
        EnvironmentTwig $env
    ) {
        if (!empty($this->contentObjectDataBackup)) {
            $env->getContentObject()->data = $this->contentObjectDataBackup;
            $this->contentObjectDataBackup = null;
        }
    }
}
