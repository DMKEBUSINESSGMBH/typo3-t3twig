
# Date Extension

This extensions adds some date functions for TWIG

## t3strftime

The TWIG build in date function uses `DateTimeInterface::format` which dows not support locales.

`t3strftime` uses the [strftime](http://php.net/manual/en/function.strftime.php) method, wich supports locales.
So you can render day or month as names in your language.

```twig 
{{ '2017-07-23T16:00:00Z'|t3strftime('%e. %B %G %H:%M Uhr') }}
```
Outputs in Berlin Timezone:
```
23. Juli 2017 18:00 Uhr
```
