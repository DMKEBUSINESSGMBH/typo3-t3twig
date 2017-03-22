
# Image Handler

This extensions allows you to render images in various ways.

## t3image

Returns an img-Tag for the given image(path, id oder FAL reference).

```twig
{{ t3image(item.image, {'file.': {'maxH' : 635}}) }}
```


## t3fetchReferences

Returns an array of [\tx_rnbase_model_media objects](https://github.com/digedag/rn_base/blob/master/model/class.tx_rnbase_model_media.php) for any item with a field which contains FAL references

```twig
{% set imageReferences = t3fetchReferences('tx_cal_event', item.uid, 'image') %}
<ul>
    {% for image in imageReferences %}
        <li>
            {{
                t3image(
                    image,
                    {
                        'file': {
                            'maxH' : 635
                        },
                        'altText' : image.titel,
                        'titleText' : image.titel
                    }
                )
            }}
        </li>
    {% endfor %}
</ul>
```
