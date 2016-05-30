# Getting Started
[Table of Contents](../index.md)
##rn_base Plugin
First of all you need a [rn_base plugin](https://github.com/digedag/rn_base/blob/master/Documentation/fe_plugins.md) with an action which extends `tx_rnbase_action_BaseIOC`.

Here you have to write all data into the $viewData object via `$viewData->offsetSet('key', 'value')` and `return null`

Also you have to override the `getViewClassName()` function to use twig rendering
```php
public function getViewClassName()
{
    return 'DMK\T3twig\View\BaseTwigView';
}
```
and the `protected function getTemplateName() {return 'templateName';}` to define your template name. Now the `templateName.html.twig` file is used from your templatePath which was configured via TS.


Of cause it's also possible to use a [tx_mklib_action_AbstractList](https://github.com/DMKEBUSINESSGMBH/typo3-mklib/blob/master/action/class.tx_mklib_action_AbstractList.php).

```php
<?php

namespace Vendor\Package\Action;

/**
 * Class ListDatasetsAction
 *
 * @package Vendor\Package\Action
 */
class ListDatasetsAction extends \tx_mklib_action_AbstractList
{
    /**
     * @return \Exception|object
     */
    protected function getRepository()
    {
        return \tx_rnbase::makeInstance('Vendor\Package\Repository\DatasetRepository');
    }

    /**
     * Returns the name of the view class
     *
     * @return string
     */
    public function getViewClassName()
    {
        return 'DMK\T3twig\View\BaseTwigView';
    }

    /**
     * @return string
     */
    protected function getTemplateName() { return 'listDatasets'; }
}
```
