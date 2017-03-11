# TWIGTEMPLATE ContentObject

[Table of Contents](../../README.md)


## Usage

There is a ContentObject called `TWIGTEMPLATE` which can be used in TypoScript.

This TypoScript example can be used to configure a page template in TYPO3:
```
example = TWIGTEMPLATE
example {
	file = CASE
	file {
		key.data = levelfield:-1, backend_layout_next_level, slide
		key.override.field = backend_layout
		default = TEXT
		default.value = EXT:myext/Resources/Private/Templates/Twig/page_default.html.twig
		2 = TEXT
		2.value       = EXT:myext/Resources/Private/Templates/Twig/page_main.html.twig
		3 = TEXT
		3.value       = EXT:myext/Resources/Private/Templates/Twig/page_gallery.html.twig
	}
	context {
		content < styles.content.get
		content_sidebar < styles.content.get
		content_sidebar.select.where = colPos=1
		pid = 5
		title = Test
	}
}
```

Content of the Twig template from above:
```twig
<div class="row-container">
  <div class="container">
    <div class="content-inner row">   
       <div id="component" class="span8">
         {{ content|raw }}
       </div>        
       <!-- Right sidebar -->
       <div id="aside-right" class="span4">
         {{ content_sidebar|raw }}
       </div>
    </div>
  </div>
</div>
<div class="container">
  <h4>{{ tsfe.page.title }} - {{ title|t3link(pid) }}</h4>
</div>
```

Result:
```html
<div class="row-container">
  <div class="container">
    <div class="content-inner row">   
       <div id="component" class="span8">
         some page content
       </div>        
       <!-- Right sidebar -->
       <div id="aside-right" class="span4">
         some sidebar content
       </div>
    </div>
  </div>
</div>
<div class="container">
  <h4>CurrentPageTitle - <a href="/index.php?id=5">Test</a></h4>
</div>
```
The context will always contain three reserved variables:
* data -> the current cObj data record
* current -> the *currentValue* of cObjects data record 
* page -> the current page record

## Usage with GridElements

It is easily possible to render GridElements with TWIGTEMPLATE. The following example 
is based on [a tutorial for FLUID](http://www.marmalade.de/magazin/2013/09/typo3-fluid-und-grid-elements-gemeinsam-verwenden/).

Configure a gridelement with this configuration:

```
backend_layout {
    colCount = 3
    rowCount = 2
    rows {
        1 {
            columns {
                1 {
                    name = Left
                    rowspan = 2
                    colPos = 11
                }
                2 {
                    name = Top
                    colspan = 2
                    colPos = 12
                }
            }
        }
        2 {
            columns {
                1 {
                    name = BottomLeft
                    colPos = 22
                }
                2 {
                    name = BottomRight
                    colPos = 23
                }
            }
        }
    }
}
```
Now configure rendering by Typoscript:

```
tt_content.gridelements_pi1.20.10.setup {
    # UID of Gridelement
    1 < lib.gridelements.defaultGridSetup
    1 {
        cObject = TWIGTEMPLATE
        cObject {
            file = fileadmin/template/gridelements/layout.html.twig
        }
    }
}
```
Finally the Twig-Template for rendering:

```twig
<div class="row">
    <div class="column small-4">
        {{data.tx_gridelements_view_column_11|raw}}
    </div>
    <div class="column small-8">
        <div class="row">
            <div class="column small-12">
                {{data.tx_gridelements_view_column_12|raw}}
            </div>
        </div>
        <div class="row">
            <div class="column small-6">
                {{data.tx_gridelements_view_column_22|raw}}
            </div>
            <div class="column small-6">
                {{data.tx_gridelements_view_column_23|raw}}
            </div>
        </div>
    </div>
</div>
```
That's it!
