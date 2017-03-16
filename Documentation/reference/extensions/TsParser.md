
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
    {{ t3cObject({'ts_path' : 'lib.testlink', 'data' : {'pid': 1, 'label': 'Wohoo'}}) }}
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
    {{ t3stdWrap({'ts_path' : 'lib.testtext', 'current_value': 'foo'}) }}
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
    {{ t3stdWrap({'ts_path' : 'lib.raw'}) }}
```

```twig
    <!-- dumps the array ["key" => "value"] -->
    {{ dump(t3stdWrap({'ts_path' : 'lib.raw'})) }}
```

## t3parseField

Renders an field with TS configuration.

```
plugin.tx_myplugin {
    confId {
        descriptionListCrop = TEXT
        descriptionListCrop.field = description
        descriptionListCrop.crop = 320 | ... | 1
    }
}
```

```twig
{{ item.row|t3parseField('descriptionListCrop') }}
```


If a flattened array of the record isn't still needed you can hand over an empty array to the extension.

```
plugin.tx_myplugin {
    confId {
        onlyText = TEXT
        onlyText.value = ExampleText
    }
}
```

```twig
{{ []|t3parseField('onlyText') }}
```
