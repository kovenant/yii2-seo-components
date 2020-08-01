# yii2-seo-components
This pack.

[![Latest Version](https://img.shields.io/packagist/v/kovenant/yii2-seo-components.svg?style=flat-square)](https://packagist.org/packages/kovenant/yii2-seo-components)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

Installation
------------

The preferred way to install this extension via [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist kovenant/yii2-seo-components "*"
```

or add this code line to the `require` section of your `composer.json` file:

```json
"kovenant/yii2-seo-components": "*"
```

Usage
-----

Add SeoModelBehavior to your model

{id} and {alias} will be replaced to attribute value with such name

````php
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => SeoModelBehavior::class,
                'route' => ['/catalog/view', 'id' => '{id}', 'alias' => '{alias}']
            ],
        ];
    }
````

Behavior provides three methods:
   * `$model->getRouteUrl()` 
   will return route as array. Now you can use it for yii\widgets\Menu items 'url'
   
    Array
    (
        [0] => catalog/item
        [categoryId] => 1
        [categoryAlias] => category-alias
        [id] => 5
        [alias] => item-alias
    )

   * `$model->getUrl()` will return for example `/catalog/1-category-alias/5-item-alias.html`

   * `$model->getAbsoluteUrl()` return `https://example.com/catalog/1-category-alias/5-item-alias.html`
   
Don't forget check your config file for pretty url settings

E.g.

````php
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '.html',
            'rules' => [
                'catalog/<categoryId:\d+>-<categoryAlias>/<id:\d+>-<alias>' => 'catalog/item',
            ],
        ],
    ],
````

###### New yii2 seo components will be added to this pack soon