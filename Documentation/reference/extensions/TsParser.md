
# TSParser

This extensions allows you to parse any field you have defined in TS.

## t3cObject

Creates output based on TypoScript.

This example shopuld create a link with label "Wohoo" wrapped by a linkt o the page with uid 1.

```
    lib.testlink = TEXT
    lib.testlink {
        field = label
        typolink.parameter.field = pid
    }
```

```twig
    {{ t3cObject('lib.testlink', {'data' : {'pid': 1, 'label': 'Wohoo'}}) }}
```

## t3stdWrap

Creates output based on TypoScript.

This example shopuld output `foo`.

```
    lib.testtext = TEXT
    lib.testtext {
        current = 1
    }
```

```twig
    {{ t3stdWrap('lib.testtext', {'current_value': 'foo'}) }}
```

## t3tsRaw

Reads the raw content of the given typoscript path.

```
    lib.raw = Foo
    lib.raw {
        key = value
    }
```

```twig
    <!-- creates the output "Foo" -->
    {{ t3stdWrap('lib.raw') }}
```

```twig
    <!-- dumps the array ["key" => "value"] -->
    {{ dump(t3stdWrap('lib.raw')) }}
```

## t3rte

Renders a string by passing it to the TYPO3 parseFunc_RTE.

```twig
    <!-- Renders the Field "bodytext" as RTE -->
    {{ item.bodytext|t3rte() }}
```

## t3parseFunc

Renders a string by passing it to a TYPO3 parseFunc.

```twig
    <!-- Renders the Field "bodytext" as RTE -->
    {{ t3parseFunc('lib.parseFunc_RTE', {'current_value' : item.bodytext}) }}
```
