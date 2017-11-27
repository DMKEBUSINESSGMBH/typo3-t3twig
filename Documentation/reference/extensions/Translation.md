# Translation

This extensions allows you to render translated labels in your plugin.

## t3trans

Translate a label

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
            <label index="label_error">An error has occurred</label>
        </languageKey>
        <languageKey index="de" type="array">
            <label index="label_error">An error has occurred!</label>
            <label index="label_error_placeholder">Error %number% has occurred!</label>
        </languageKey>
    </data>
</T3locallang>

```

```twig
{{ 'label_error'|t3trans }}
```

[Optional] Replace placeholders
```twig
{{ 'label_error_placeholder'|t3trans({'%number%':4}) }}
```
Output: Error 4 has occurred!
