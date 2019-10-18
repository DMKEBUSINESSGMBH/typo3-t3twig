<?php

namespace DMK\T3twig\ViewHelpers;

use DMK\T3twig\Twig\RendererTwig as Renderer;

/**
 * This file is part of the "t3twig" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * "ViewHelper" to render template by Twig.
 *
 * # Example: Basic example
 * <code>
 * <t:renderTwig path="{settings.cssFile}" />
 * </code>
 * <output>
 * The rendered twig output.
 * </output>
 */
class RenderTwigViewHelper extends BaseViewHelper
{
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        $this->registerArgument('template', 'string', 'Path to twig template', true);
        $this->registerArgument('settings', 'array', 'TS settings', false, false);
    }

    public function render(array $context = [])
    {
        $template = $this->arguments['template'];
        $settings = $this->arguments['settings'];
        $tsfe = \tx_rnbase_util_TYPO3::getTSFE();

        $configurations = $this->buildConfigurations($settings, $tsfe->cObj);
        $renderer = Renderer::instance(
            $configurations,
            '',
            $template
        );
        $content = $renderer->render($context);

        return $content;
    }

    /**
     * Builds the  configuration object based on the conf.
     *
     * @param array $conf
     *
     * @return \Sys25\RnBase\Configuration\ConfigurationInterface
     */
    private function buildConfigurations(array $conf, $cObj)
    {
        /* @var $configurations \Sys25\RnBase\Configuration\ConfigurationInterface */
        $configurations = \tx_rnbase::makeInstance(
            'Tx_Rnbase_Configuration_Processor'
        );
        $configurations->init(
            $conf,
            $cObj,
            't3twig',
            't3twig'
        );

        return $configurations;
    }
}
