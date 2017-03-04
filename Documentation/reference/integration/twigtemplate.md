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
		default.value = EXT:myext/Resources/Private/Template/Twig/page_default.html.twig
		2 = TEXT
		2.value       = EXT:myext/Resources/Private/Template/Twig/page_main.html.twig
		3 = TEXT
		3.value       = EXT:myext/Resources/Private/Template/Twig/page_gallery.html.twig
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
  <h4>{{ page.title }} - {{ title|t3link(pid) }}</h4>
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
