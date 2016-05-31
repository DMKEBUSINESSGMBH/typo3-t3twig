[Extensions](../extensions.md)


# Translation
## t3trans

Translated labels in your plugin.

```
plugin.tx_myplugin {
	locallangFilename = EXT:myplugin/Resources/Private/Language/locallang.xml
}
```

```xml
<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
<T3locallang>
	<meta type="array">
		<description>language strings</description>
		<type>database</type>
		<csh_table></csh_table>
		<fileId>EXT:myplugin/Resources/Private/Language/locallang.xml</fileId>
	</meta>
	<data type="array">
		<languageKey index="default" type="array">
			<label index="lable_error">An error has occurred</label>
		</languageKey>
		<languageKey index="de" type="array">
			<label index="lable_error">Ein Fehler ist aufgetreten!</label>
		</languageKey>
	</data>
</T3locallang>

```

```twig
{{ 'lable_error'|t3trans }}
```
