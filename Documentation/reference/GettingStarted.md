# Getting Started

[Table of Contents](../README.md)


## ContentObject

There is a ContentObject called `T3TWIG` which you can use in TypoScript.  
The data from te TS and the current context, usually the page, are aviable at the template.

TypoScript example:
```
example = T3TWIG
example {
	template = EXT:myext/Resources/Private/Template/Twig/Headline.html.twig 
	pid = 5
	title = Test
}
```

Content of the Twig template from above:
```twig
<h4>{{ header }} - {{ title|t3link(pid) }}</h4>
```

Result:
```html
<h4>CurrentPageTitle - <a href="/index.php?id=5">Test</a></h4>
```


##rn_base Plugin

First of all you need a [rn_base plugin](https://github.com/digedag/rn_base/blob/master/Documentation/fe_plugins.md) with an action which extends `tx_rnbase_action_BaseIOC`.

Here you have to write all data into the $viewData object via `$viewData->offsetSet('key', 'value')` and `return null`

Also you have to override the `getViewClassName()` function to use twig rendering
```php
public function getViewClassName()
{
    return 'DMK\T3twig\View\RnBaseView';
}
```
and the `protected function getTemplateName() {return 'templateName';}` to define your template name. Now the `templateName.html.twig` file is used from your templatePath which was configured via TS.


### Usage for ThirdParty Plugins

For existing plugins, the view class can be set by TS.
So you can use [MK SEARCH](https://github.com/DMKEBUSINESSGMBH/typo3-mksearch/),
or any other [rn_base](https://github.com/digedag/rn_base) based Plugin, with twig templates.

```
    plugin.tx_mksearch {
        ### set the template path for the solr action to the twig template. can be done in flexform to.
        searchsolrTemplate = EXT:mksearchtwig/Resources/Private/Template/Extensions/MkSearch/SearchSolr.html.twig
        
        #### set the twig view
        searchsolr.viewClassName = DMK\T3twig\View\RnBaseView
    }
```

In the template you can access the all the data from the viewdata like this:
```twig
<ul>
    {% for item in result.items %}
        <li>
            <h4>{{ item.record.title|t3link(item.record.pid) }}</h4>
            <p>{{ item.record.content }}</p>
        </li>
    {% endfor %}
</ul>
```


### Usage with own Actions and ThirdParty Dependencies

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
        return 'DMK\T3twig\View\RnBaseView';
    }

    /**
     * @return string
     */
    protected function getTemplateName() { return 'listDatasets'; }
}
```
