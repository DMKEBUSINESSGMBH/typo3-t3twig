<?php

namespace DMK\T3twig\View;

/***************************************************************
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use DMK\T3twig\Twig\RendererTwig as Renderer;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

/**
 * Class ExtbaseView.
 *
 * @category TYPO3-Extension
 *
 * @author   Eric Hertwig
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @see     https://www.dmk-ebusiness.de/
 */
class ExtbaseView implements ViewInterface
{
    /**
     * @var ControllerContext
     */
    protected $controllerContext;

    /**
     * @var \Sys25\RnBase\Configuration\Processor
     */
    protected $configuration;

    /**
     * Sets the current controller context.
     *
     * @param ControllerContext $controllerContext Controller context which is available inside the view
     *
     * @api
     */
    public function setControllerContext(ControllerContext $controllerContext)
    {
        $this->controllerContext = $controllerContext;
    }

    /**
     * Add a variable to the view data collection.
     * Can be chained, so $this->view->assign(..., ...)->assign(..., ...); is possible.
     *
     * @param string $key   Key of variable
     * @param mixed  $value Value of object
     *
     * @return \TYPO3\CMS\Extbase\Mvc\View\ViewInterface an instance of $this, to enable chaining
     *
     * @api
     */
    public function assign($key, $value)
    {
        $this->configuration->getViewData()->offsetSet($key, $value);

        return $this;
    }

    /**
     * Add multiple variables to the view data collection.
     *
     * @param array $values array in the format array(key1 => value1, key2 => value2)
     *
     * @return \TYPO3\CMS\Extbase\Mvc\View\ViewInterface an instance of $this, to enable chaining
     *
     * @api
     */
    public function assignMultiple(array $values)
    {
        foreach ($values as $key => $value) {
            $this->assign($key, $value);
        }

        return $this;
    }

    /**
     * Tells if the view implementation can render the view for the given context.
     *
     * @param ControllerContext $controllerContext
     *
     * @return bool TRUE if the view has something useful to display, otherwise FALSE
     *
     * @api
     */
    public function canRender(ControllerContext $controllerContext)
    {
        $this->setControllerContext($controllerContext);
        $this->configuration = $this->buildConfigurations();

        return true;
    }

    /**
     * Renders the view.
     *
     * @return string The rendered view
     *
     * @api
     */
    public function render()
    {
        $renderer = Renderer::instance(
            $this->configuration,
            $this->controllerContext->getRequest()->getControllerActionName().'.'
        );

        return $renderer->render($this->configuration->getViewData()->getArrayCopy());
    }

    /**
     * Initializes this view.
     *
     * @api
     */
    public function initializeView()
    {
    }

    /**
     * Builds the  configuration object based on the conf.
     *
     * @param array $conf
     *
     * @return \Sys25\RnBase\Configuration\Processor
     */
    private function buildConfigurations()
    {
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);

        $conf = $configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            $this->controllerContext->getRequest()->getControllerExtensionKey(),
            $this->controllerContext->getRequest()->getPluginName()
        );

        $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);
        $conf = $typoScriptService->convertPlainArrayToTypoScriptArray($conf);

        /* @var $configurations \Sys25\RnBase\Configuration\Processor */
        $configurations = GeneralUtility::makeInstance(
            \Sys25\RnBase\Configuration\Processor::class
        );
        $configurations->init(
            $conf,
            null,
            't3twig',
            't3twig'
        );

        return $configurations;
    }
}
