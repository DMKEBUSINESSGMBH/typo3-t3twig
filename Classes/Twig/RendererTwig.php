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
 * Class renderer
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig
 * @author   Michael Wagner
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class RendererTwig
{
    protected $conf;
    protected $configurations;
    protected $confId;

    /**
     * An instance of this renderer
     *
     * @param array $config
     * @param \tx_rnbase_configurations $configurations
     * @param string $confId
     *
     * @return \DMK\T3twig\Twig\RendererTwig
     */
    public static function instance(
        \tx_rnbase_configurations $configurations,
        $confId = '',
        array $conf = []
    ) {
        return new self(
            $configurations,
            $confId,
            $conf
        );
    }

    /**
     * Constructor
     *
     * @param \tx_rnbase_configurations $configurations
     * @param string $confId
     * @param array $conf
     *
     * @return void
     */
    public function __construct(
        \tx_rnbase_configurations $configurations,
        $confId = '',
        array $conf = []
    ) {
        if(isset(\tx_rnbase_util_TYPO3::getTSFE()->tmpl->setup['lib.']['tx_t3twig.'])) {
            $this->conf = \tx_rnbase_util_Arrays::mergeRecursiveWithOverrule(
    		    $conf,
    	        \tx_rnbase_util_TYPO3::getTSFE()->tmpl->setup['lib.']['tx_t3twig.']
            );
        }
        $this->configurations = $configurations;
        $this->confId = $confId;
    }

    /**
     * The current configurations
     *
     * @return \Tx_Rnbase_Configuration_ProcessorInterface
     */
    public function getConfigurations()
    {
        return $this->configurations;
    }

    /**
     * The current confid
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
        $paths = $this->conf['templatepaths.'];
        // add the paths for the current render context
        $paths = \tx_rnbase_util_Arrays::mergeRecursiveWithOverrule(
            $paths,
            $this->getConfigurations()->getExploded(
                $this->getConfId() . 'templatepaths.'
            )
        );

        // add the paths fro the current config
        $paths = \tx_rnbase_util_Arrays::mergeRecursiveWithOverrule(
            $paths,
            $this->getConfigurations()->getExploded(
                'templatepaths.'
            )
        );

        return $paths;
    }
    /**
     * The extensions to use in twig templates
     *
     * @return string
     */
    protected function getExtensions()
    {
        // initial use the global paths
        $paths = $this->conf['extensions.'];

        // add the paths for the currend reder context
        $paths = \tx_rnbase_util_Arrays::mergeRecursiveWithOverrule(
            $paths,
            $this->getConfigurations()->getExploded(
                $this->getConfId() . 'extensions.'
            )
        );

        // add the paths fro the current config
        $paths = \tx_rnbase_util_Arrays::mergeRecursiveWithOverrule(
            $paths,
            $this->getConfigurations()->getExploded(
                'extensions.'
            )
        );

        return $paths;
    }

    /**
     * The template path with the tempate for the current renderer
     *
     * @return string
     * @throws T3TwigException
     */
    protected function getTemplatePath()
    {
        $path = $this->getConfigurations()->get($this->getConfId() . 'file', true);
        $path = $path ?: $this->getConfigurations()->get($this->getConfId() . 'template', true);
        $path = $path ?: $this->getConfigurations()->get('template'); // Warum wird hier ohne confid gesucht??

        if (empty($path)) {
            if (!empty($this->conf['template'])) {
                $path = $this->conf['template'];
            }
        }

        // TODO: Wozu dieser Aufwand??
        // if there is no path, put the rnbase template path before
        if (!empty($path) && strpos($path, '/') === false) {
            // check the rnbase base path
            $basePath = $this->getConfigurations()->get('templatePath');
            // add the first template include path
            $basePath = $basePath ?: reset($this->conf['templatepaths.']);
            if (!empty($basePath)) {
                $path = $basePath . '/' .$path;
            }
        }
        if(empty($path)) {
            throw new T3TwigException('Neither "file" nor "template" configured for twig template.');
        }

        return \tx_rnbase_util_Files::getFileAbsFileName(
            $path
        );
    }

    public function render(
        array $data = null
    ) {
        $templateFullFilePath = $this->getTemplatePath();
        $twigLoader = UtilityTwig::getTwigLoaderFilesystem(
            dirname($templateFullFilePath)
        );

        UtilityTwig::injectTemplatePaths(
            $twigLoader,
            $this->getTemplatePaths()
        );


        $twigEnv = UtilityTwig::getTwigEnvironment($twigLoader)
            ->setRenderer($this)
        ;

        UtilityTwig::injectExtensions(
            $twigEnv,
            $this->getExtensions()
        );

        /** @var $template \Twig_Template */
        $template = $twigEnv->loadTemplate(
            basename($templateFullFilePath)
        );

        if (!is_array($data)) {
            $data = $this->getConfigurations()->getCObj()->data;
        }

        $result = $template->render($data);

        return $result;
    }
}
