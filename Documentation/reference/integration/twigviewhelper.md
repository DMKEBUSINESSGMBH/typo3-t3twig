# Fluid ViewHelper

[Table of Contents](../../README.md)


## Usage

Frustrated by Fluid? Use Twig instead!

There is a "ViewHelper" called `renderTwig` that let you step over to Twig.

Simply load the namespace and render your Twig template like this:

```
<html xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers"
	  xmlns:t="http://typo3.org/ns/DMK\T3twig\ViewHelpers">

<t:renderTwig 
	template="EXT:t3twig/Resources/Private/Template/ExampleVH.html.twig" 
	context="{title: 'Twig-ViewHelper', description: 'Render Twig templates from within Fluid!'}" 
	settings="{settings}"
 />

```

Content of the Twig template from above:

```twig
    <h2>{{ title }}</h2>
    {{
        t3image(
            'EXT:t3twig/ext_icon.png',
            {
                'params' : 'style="float:left; margin: 0 10px 10px 0;"'
            }
        )
    }}
    <p>
        {{ description }}
        <br />
        {{
            'Dies ist ein Hyperlink'|t3link(
            {
                'destination' : url,
                'ts_config' : {
                    "extTarget" : '_blank',
                    "atagparams" : {
                        "style" : "color: white; text-decoration: underline; font-weight: bold;"
                    }
                }
            }
            )
        }}
        <br />
    </p>
```

Result:

```html
    <h2>Twig-ViewHelper</h2>
    <img src="typo3conf/ext/t3twig/ext_icon.png" width="64" height="64"  style="float:left; margin: 0 10px 10px 0;"  alt="" >
    <p>
        Render Twig templates from within Fluid!
        <br />
        <a href="/" style="color: white; text-decoration: underline; font-weight: bold;">Dies ist ein Hyperlink</a>
        <br />
    </p>
```

