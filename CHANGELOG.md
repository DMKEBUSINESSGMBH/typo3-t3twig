(add new changes on top of this file and don't forget the documentation!)

# Changes

## v12.0.2
* Fix PHP warnings

## v12.0.1
* Fix TER release

## v12.0.0
* Add support for TYPO3 11.5 and 12.4
* Drop support for TYPO3 9.5 and 10.4
* Breaking Change: rename setup.txt to setup.typoscript

## v10.1.0
* Require rn_base >= 1.15.0

## v10.0.2
* fix adding of context variable in twig viewhelper

## v10.0.1
* fixed description so TER release works again

## v10.0.0
* Add support for TYPO3 10.4
* Add ExtbaseView
* Integrate caching framework
* bugfixes and maintenance
* Remove Support for TYPO3 6.2, 7.5 and 8.7
* fix cache key generation

## v2.0.6
* added TYPO3 9.5 support
* added documentation
* New extension dbrelation to lookup related entities
* Fluid viewhelper to render Twig templates
* Fix last and next link in pagination macro

## v2.0.3
* Add support for TYPO3 6.2
* added documentation

## v2.0.2
* Add placeholder support for translations

## v2.0.1
* clean up and bugfixes

## v2.0.0
 * TYPO3 8.7 LTS Support
 * new parseFunc and RTE functions

## v1.1.0
 * new getter for content object in twig enviroment
 * new support to set the context data in content object to use fields in ts
 * refactoring of some functions to new arguments array  
   Warning: breakting changes in t3link and t3url.  
   you can use the backward methods t3linkOld and t3urlOld methods or has to refactor to new arguments. see [documentation](Documentation/reference/extensions/Link.md).
 * new t3cObject, t3stdWrap and t3tsRaw functions added

## v1.0.1
 * typo3 cms dependencies fixed

## v1.0.0
 * Initial release
