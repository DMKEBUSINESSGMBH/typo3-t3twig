
# TSParser

This extensions adds the `$GLOBALS['TSFE']` object to the template context and handles the tsfe manipulation.

So you can access the Page properties like this:
```twig
    {{ tsfe.page.title }}
```

## tsfePagetitle

Sets the title tag for the current page.

```twig
    {{ tsfePagetitle(tsfe.indexedDocTitle ~ ' | News: ' ~ news.title) }}
```
