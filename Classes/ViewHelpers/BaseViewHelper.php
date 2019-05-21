<?php
namespace DMK\T3twig\ViewHelpers;

/**
 * This file is part of the "t3twig" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

if (\tx_rnbase_util_TYPO3::isTYPO90OrHigher()) {
    class BaseViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper {}
}
else {
    class BaseViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {}

}