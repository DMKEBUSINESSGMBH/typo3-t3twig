# Namespaces

[Table of Contents](../../README.md)

## Include templates
The fileloader for TYPO3 will register a namespace for each TYPO3 extension with an existing folder `Resources/Private/Templates/`. 
So if your extension **myext** has a file under `EXT:myext/Resources/Private/Templates/base.html.twig`, the correct resource
name in Twig will be `@EXT:myext/base.html-twig`.

You can also use the namespace `@fileadmin` to access templates from TYPO3 fileadmin folder.

## Usage example
Basetemplate under EXT:ext1/Resources/Private/Templates/Layout/layout.html.twig
```twig
<div id="content">{% block content %}{% endblock %}</div>
<div id="footer">
    {% block footer %}
        &copy; Copyright 2017 by <a href="http://domain.invalid/">you</a>.
    {% endblock %}
</div>
```
Use this child template to include layout:

```twig
{% extends "@EXT:ext1/Layouts/layout.html.twig" %}
{% block content %}
    <h1>Index</h1>
    <p class="important">
        Welcome on my awesome homepage.
    </p>
{% endblock %}
{% block footer %}
    {{ parent() }} Some other stuff.
{% endblock %}
```

