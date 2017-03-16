
# Link Handler

This extensions allows you to render a link via [tx_rnbase_util_Link](https://github.com/digedag/rn_base/blob/master/util/class.tx_rnbase_util_Link.php).

## t3url

Renders an URL

```twig
{{
	t3url(
		{
			'destination' : $destination,
			'params' : [],
			'ts_path' : 'link.',
			'data' : optionalContentObjectData,
			'config' : {"atagparams." : {"class" : "btn btd-default"}}
		}
	)
}}
```

In the `$params` array you can  hand over some parameters which are added as get variables to your url

The `$destination` could contain one of the following values:
* pageId
* page alias
* external url ...
[@see TSref => typolink => parameter](https://docs.typo3.org/typo3cms/TyposcriptReference/Functions/Typolink/Index.html)


As an optional you can provide a tsPath to init the link via TypoScript. For more information @see initByTS() - function in \tx_rnbase_util_Link()

You can add an overrule ts config array as fourth parameter.
You optionaly can assign this config aray as third parameter, if you don't need a config from the current tspath. 

## t3link

Renders a full qualified a tag.

```twig
{{
	'Label'|t3link
		{
			'destination' : $destination,
			'params' : [],
			'ts_path' : 'link.',
			'data' : optionalContentObjectData,
			'config' : {"atagparams." : {"class" : "btn btd-default"}}
		}
	)
}}
```

The only difference to `t3url` is that you can add a label which is rendered within the a-Tag.
