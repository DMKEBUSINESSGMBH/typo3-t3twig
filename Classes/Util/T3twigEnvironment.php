<?php

namespace DMK\T3twig\Util;

use DMK\T3twig\View\BaseTwigView;

/**
 * Class T3twigEnvironment
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig\Util
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class T3twigEnvironment extends \Twig_Environment
{
    /** @var BaseTwigView */
    protected $view;

    /**
     * @param BaseTwigView $view
     */
    public function setView(BaseTwigView $view)
    {
        $this->view = $view;
    }

    /**
     * @return \tx_rnbase_configurations
     */
    public function getConfigurations()
    {
        return $this->view->getController()->getConfigurations();
    }

    /**
     * @return string
     */
    public function getConfId()
    {
        return $this->view->getController()->getConfId();
    }
}
