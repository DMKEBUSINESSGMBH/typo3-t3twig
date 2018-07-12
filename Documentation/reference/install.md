# Install guide 

## Prerequisite

You need to run TYPO3 in Composer mode! Install from TER is not supported as T3twig is shipped without Twig itself.

### Special prerequisite for TYPO3 6.2 only

Check out [install guide in TYPO3 Wikipage](https://wiki.typo3.org/Composer#TYPO3_6.2.x).
It is very important to rename your vendor folder `Packages/Libraries` and to set environment variable `TYPO3_COMPOSER_AUTOLOAD`.

## Install

As usual with composer add a new requirement in your project.

```
composer.phar require dmk/t3twig
```

After this enable **T3twig** in extension manager and add static typoscript template **T3twig (t3twig)** to your page setup. Ensure there is some new typoscript configuration under `lib.tx_t3twig.`

