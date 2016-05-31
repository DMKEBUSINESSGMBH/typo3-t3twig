[Extensions](../extensions.md)


# TSParser
## t3parseField

This extensions allows you to parse any field you have defined in TS. A flattened data array of the item is always needed.

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
