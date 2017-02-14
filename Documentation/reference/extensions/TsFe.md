
# TSParser

This extensions adds the `$GLOBALS['TSFE']` object to the template context and handles the tsfe manipulation.

## tsfePagetitle

Sets the title tag for the current page.

```twig
    {{ tsfePagetitle(tsfe.indexedDocTitle ~ ' | News: ' ~ news.title) }}
```
