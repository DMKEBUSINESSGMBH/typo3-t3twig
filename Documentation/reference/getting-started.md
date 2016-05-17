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
and the `	protected function getTemplateName() {return 'templateName';}` to define your template name. Now the `templateName.html.twig` file is used from your templatePath which was configured via TS.