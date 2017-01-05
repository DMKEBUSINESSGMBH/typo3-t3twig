[Extensions](../extensions.md)

# Image Handler

This extensions allows you to render images in various ways.

## t3images

Returns an array of img-Tags for an item which implements the [\Tx_Rnbase_Domain_Model_DomainInterface](https://github.com/digedag/rn_base/blob/master/Classes/Domain/Model/DomainInterface.php) and has a field which contains FAL references

```twig
{% for img in item|t3images($refField = 'images') %}
    {{ img|raw }}
{% endfor %}
```

Optionally you can configure the images via TS
```
plugin.tx_myplugin {
    confId {
        refField {
            file.import.field = refField
            file.maxW = 1024c
            ...
        }
    }
}
```

## t3genericImages

Returns an array of img-Tags for an item (see getGenericMediaObjects) with a field which contains FAL references.

```
plugin.tx_myplugin {
    $confId {
        $tsPathConfig {
            file.maxW = 270
        }
    }
}
```
```twig
{% set img = t3genericImages('tx_cal_event', item.row.uid, $refField = 'images', $tsPathConfig = 'images') %}
```
## t3image

Returns an img-Tag for the given image(path, id oder FAL reference).

```
```twig
{{ t3image(image.file_path, {'file.': {'maxH' : 635}}) }}
```


## getMediaObjects

Returns an array of [\tx_rnbase_model_media objects](https://github.com/digedag/rn_base/blob/master/model/class.tx_rnbase_model_media.php) for an item which implements the [\Tx_Rnbase_Domain_Model_DomainInterface](https://github.com/digedag/rn_base/blob/master/Classes/Domain/Model/DomainInterface.php) and has a field which contains FAL references

```twig
{% set images = item|getMediaObjects($refField = 'images') %}
```


## getGenericMediaObjects

Returns an array of [\tx_rnbase_model_media objects](https://github.com/digedag/rn_base/blob/master/model/class.tx_rnbase_model_media.php) for any item with a field which contains FAL references

```twig
{% set imagesGeneric = getGenericMediaObjects('tx_cal_event', item.row.uid, 'image') %}
```


## t3imageFromTS

Renders a [cImage](https://docs.typo3.org/typo3cms/TyposcriptReference/ContentObjects/Image/Index.html) for a given TS path and returns full img-tag

```
plugin.tx_myplugin {
    $tsPath {
        file = EXT:myplugin/Resources/Public/Images/img.png
        imgOption1 =
        ...
    }
}
```
```twig
{{ $tsPath|t3imageFromTS }}
```
