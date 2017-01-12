<?php

namespace DMK\T3twig\Twig;

use DMK\T3twig\Util\T3twigEnvironment;

/**
 * Class LinkExtension
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig\Twig
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class LinkExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                't3link', [$this, 'renderLink'],
                ['needs_environment' => true, 'is_safe' => ['html']]
            ),
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                't3url', [$this, 'renderUrl'],
                ['needs_environment' => true]
            ),
        ];
    }

    /**
     * @param T3twigEnvironment $env
     * @param                   $label
     * @param                   $dest
     * @param array             $params
     * @param string            $tsPath
     *
     * @return array
     */
    public function renderLink(T3twigEnvironment $env, $label, $dest, $params = [], $tsPath = 'link.')
    {
        $rnBaseLink = $this->makeRnbaseLink($env, $label, $dest, $params, $tsPath);

        return $rnBaseLink->makeTag();
    }

    /**
     * @param T3twigEnvironment $env
     * @param                   $dest
     * @param array             $params
     * @param string            $tsPath
     *
     * @return string
     */
    public function renderUrl(T3twigEnvironment $env, $dest, $params = [], $tsPath = 'link.')
    {
        $rnBaseLink = $this->makeRnbaseLink($env, $label = '', $dest, $params, $tsPath);

        return $rnBaseLink->makeUrl(false);
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
     * @param T3twigEnvironment $env
     * @param                   $label
     * @param                   $dest
     * @param array             $params
     * @param string            $tsPath
     *
     * @return \tx_rnbase_util_Link
     */
    private function makeRnbaseLink(T3twigEnvironment $env, $label, $dest, $params, $tsPath = 'link.')
    {
        $configurations = $env->getConfigurations();
        $confId         = $env->getConfId();

        $rnBaseLink = $configurations->createLink();
        $rnBaseLink->label($label);
        $rnBaseLink->initByTS($configurations, $confId.$tsPath, $params);
        // set destination only if set, so 0 for current page can be used
        if (!empty($dest)) {
        	$rnBaseLink->destination($dest);
        }

        if (($extTarget = $configurations->get($confId.$tsPath.'extTarget'))) {
            $rnBaseLink->externalTargetAttribute($extTarget);
        }

        return $rnBaseLink;
    }
}
