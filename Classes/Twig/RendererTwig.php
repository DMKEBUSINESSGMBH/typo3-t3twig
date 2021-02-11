<?php

namespace DMK\T3twig\Twig;

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
 * Class renderer.
 *
 * @category TYPO3-Extension
 *
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @see     https://www.dmk-ebusiness.de/
 */
class RendererTwig
{
    /**
     * Basic conf from lib.tx_t3twig.
     *
     * @var array
     */
    protected $conf;

    /**
     * Filepath to alternativ fallback template.
     *
     * @var string
     */
    protected $fallbackTemplate;

    /**
     * Filepath to alternativ fallback template.
     *
     * @var \Tx_Rnbase_Configuration_ProcessorInterface
     */
    protected $configurations;

    /**
     * Configuration path.
     *
     * @var string
     */
    protected $confId;

    /**
     * An instance of this renderer.
     *
     * @param \Tx_Rnbase_Configuration_ProcessorInterface $configurations
     * @param string                                      $confId
     * @param array                                       $conf
     *
     * @return \DMK\T3twig\Twig\RendererTwig
     */
    public static function instance(
        \Tx_Rnbase_Configuration_ProcessorInterface $configurations,
        $confId = '',
        $templateFile = ''
    ) {
        return new self(
            $configurations,
            $confId,
            $templateFile
        );
    }

    /**
     * Constructor.
     *
     * @param \Tx_Rnbase_Configuration_ProcessorInterface $configurations
     * @param string                                      $confId
     * @param array                                       $conf
     */
    public function __construct(
        \Tx_Rnbase_Configuration_ProcessorInterface $configurations,
        $confId = '',
        $templateFile = ''
    ) {
        if (isset(\tx_rnbase_util_TYPO3::getTSFE()->tmpl->setup['lib.']['tx_t3twig.'])) {
            $this->conf = \tx_rnbase_util_TYPO3::getTSFE()->tmpl->setup['lib.']['tx_t3twig.'];
        }
        $this->configurations = $configurations;
        $this->confId = $confId;
        $this->fallbackTemplate = $templateFile;
    }

    /**
     * The current configurations.
     *
     * @return \tx_rnbase_configurations
     */
    public function getConfigurations()
    {
        return $this->configurations;
    }

    /**
     * The current confId.
     *
     * @return string
     */
    public function getConfId()
    {
        return $this->confId;
    }

    /**
     * The template path to search in for macros, includes, tc.
     *
     * @return string
     */
    protected function getTemplatePaths()
    {
        // initial use the global paths
        $paths = $this->conf['templatepaths.'] ?: [];
        // add the paths for the current render context
        $paths = \tx_rnbase_util_Arrays::mergeRecursiveWithOverrule(
            $paths,
            $this->getConfigurations()->getExploded(
                $this->getConfId().'templatepaths.'
            )
        );

        return $paths;
    }

    /**
     * The extensions to use in twig templates.
     *
     * @return string
     */
    protected function getExtensions()
    {
        // initial use the global paths
        $paths = $this->conf['extensions.'] ?: [];

        // add the paths for the current render context
        $paths = \tx_rnbase_util_Arrays::mergeRecursiveWithOverrule(
            $paths,
            $this->getConfigurations()->getExploded(
                $this->getConfId().'extensions.'
            )
        );

        return $paths;
    }

    /**
     * The template path with the template for the current renderer.
     *
     * @return string
     *
     * @throws T3TwigException
     */
    protected function getTemplatePath()
    {
        $path = $this->getConfigurations()->get($this->getConfId().'file', true);
        $path = $path ?: $this->getConfigurations()->get($this->getConfId().'template', true);

        if (empty($path)) {
            $path = $this->fallbackTemplate;
        }

        // if the path only contains the filename like `Detail.html.twig`
        // so we try to add the base template path from the configuration.
        if (!empty($path) && false === strpos($path, '/')) {
            // check the rnbase base path
            $basePath = $this->getConfigurations()->get('templatePath');
            // add the first template include path
            $basePath = $basePath ?: reset((array) $this->conf['templatepaths.']);
            if (!empty($basePath)) {
                $path = $basePath.'/'.$path;
            }
        }

        if (empty($path)) {
            throw new T3TwigException('Neither "file" nor "template" configured for twig template.');
        }

        return \tx_rnbase_util_Files::getFileAbsFileName(
            $path
        );
    }

    /**
     * Renders the viewdata throu a template.
     *
     * @param array $data
     *
     * @return string The filan template
     *
     * @throws T3TwigException
     * @throws \Exception
     * @throws \TYPO3\CMS\Core\Exception
     * @throws \Throwable
     * @throws \Twig_Error_Runtime
     */
    public function render(
        array $data = null
    ) {
        $templateFullFilePath = $this->getTemplatePath();

        if (!is_file($templateFullFilePath)) {
            throw new T3TwigException('Template file not found or empty: '.$templateFullFilePath);
        }
        $twigLoader = UtilityTwig::getTwigLoaderFilesystem(
            dirname($templateFullFilePath)
        );

        UtilityTwig::injectTemplatePaths(
            $twigLoader,
            $this->getTemplatePaths()
        );

        $twigEnv = UtilityTwig::getTwigEnvironment($twigLoader)
            ->setRenderer($this);

        UtilityTwig::injectExtensions(
            $twigEnv,
            $this->getExtensions()
        );

        /**
         * @var \Twig_Template
         */
        $template = $twigEnv->loadTemplate(
            basename($templateFullFilePath)
        );

        $result = $template->render($data);

        return $result;
    }
}
