
# Add 3rd Party Extensions

Install the Requirement.  
In this case we need the truncate function of [Twig-extensions](http://twig-extensions.readthedocs.io)

```bash
composer require "twig/extensions:~1.5"
```

Register the Extension by TypoScript:

```typoscript
lib.tx_t3twig {
    extensions {
        twigExt_Text = Twig_Extensions_Extension_Text
        twigExt_I18n = Twig_Extensions_Extension_I18n
        twigExt_Intl = Twig_Extensions_Extension_Intl
        twigExt_Array = Twig_Extensions_Extension_Array
        twigExt_Date = Twig_Extensions_Extension_Date
    }
}
```

Clear the TYPO3 Caches.

Use truncate in the Twig-Template:

```twig
    <!-- Renders "Hello, Beautiful ..." -->
    {{ 'Hello, Beautiful World!' | truncate(10, true, ' ...') }}
```
