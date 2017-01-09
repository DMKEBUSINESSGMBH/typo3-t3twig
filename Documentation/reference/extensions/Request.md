
# Request

This extensions allows you to access parameters from the request like $_POST or $_GET.

## t3gp

Reads the parameter from the current qualifier.  
The folowing url should output bar `/?mksearch[foo]=bar`

```twig
    {{ t3gp('foo') }}
```

The folowing url should output baz `/?mksearch[foo][bar]=baz`

```twig
    {{ t3gp('foo|bar') }}
    {{ t3gp('foo').bar }}
```