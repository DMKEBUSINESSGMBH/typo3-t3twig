# DB-Relation Extension

This extension let you retrieve related entities from database.

## t3dbrel

Functionality is based on search classes of rn_base. So you need to provide a search class for your target entity.

### Define relation in typoscript

The following typoscript prepares a relation named `categories`.

```
plugin.tx_yourplugin {
  yourview.template {
    relations {
      categories {
        join.alias = SYS_CATEGORY_RECORD_MM
        join.field = uid_foreign
        fields.SYS_CATEGORY_RECORD_MM.tablenames.OP_EQ = tx_t3bookingplan_building
        options.limit = 10
        options.orderby.SYS_CATEGORY.TITLE = desc
        callback.class = Sys25\RnBase\Domain\Repository\CategoryRepository
        callback.method = search
      }
    }
  }
}

```

### Use in Twig template

This will load category entities based on configuration `categories` for an entity `building`.

```twig
{% set items = t3dbrel(building, {'relation': 'categories'}) %}

```
It is also possible to override options if you need this:

```twig
{% set items = t3dbrel(building, {
  'relation': 'categories',
  'options': {
    'debug': 1,
    'orderby': {'SYS_CATEGORY.TITLE': 'asc'}
  }
}) %}

```
