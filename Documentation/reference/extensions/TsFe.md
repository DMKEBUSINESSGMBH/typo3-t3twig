
# TSParser

This extensions adds the `$GLOBALS['TSFE']` and the \TYPO3\CMS\Core\Page\PageRenderer object to the template context and handles the tsfe manipulation.

So you can access the Page properties like this:
```twig
    {{ tsfe.page.title }}
```

## tsfePagetitle

Sets the title tag for the current page.

```twig
    {{ tsfePagetitle(tsfe.indexedDocTitle ~ ' | News: ' ~ news.title) }}
```

## Page Renderer
This way you can add a JavaScript file.

```twig
    {{ pageRenderer.addJsFooterLibrary(
        'googleApi', 'https://maps.googleapis.com/maps/api/js?sensor=false',
        'text/javascript', false, false, '', true, '|', true
    ) }}
```
