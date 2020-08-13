# yii2-seo-components

[![Latest Version](https://img.shields.io/packagist/v/kovenant/yii2-seo-components.svg?style=flat-square)](https://packagist.org/packages/kovenant/yii2-seo-components)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.com/kovenant/yii2-seo-components.svg?branch=master)](https://travis-ci.com/kovenant/yii2-seo-components)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kovenant/yii2-seo-components/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kovenant/yii2-seo-components/?branch=master)
[![codecov](https://codecov.io/gh/kovenant/yii2-seo-components/branch/master/graph/badge.svg)](https://codecov.io/gh/kovenant/yii2-seo-components)

Installation
------------

The preferred way to install this extension is via [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist kovenant/yii2-seo-components "*"
```

or add this code line to the `require` section of your `composer.json` file:

```json
"kovenant/yii2-seo-components": "*"
```

SeoModelBehavior Usage
-----

Add SeoModelBehavior to your model

{id}, {category_id} and {alias} will be replaced by the value of the attributes of the current model, that have such names

{category.alias} will be replaced by the value of the relation’s attribute

````php
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => \kovenant\seo\SeoModelBehavior::class,
                'route' => ['catalog/item', 'categoryId' => '{category_id}', 'categoryAlias' => '{category.alias}', 'id' => '{id}', 'alias' => '{alias}']
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

   * `$model->getAbsoluteUrl()` returns `https://example.com/catalog/1-category-alias/5-item-alias.html`
   
Don't forget to check your config file for pretty url settings

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

MetaTagsWidget.php Usage
-----

Add MetaTagsWidget to your view

````php
/** yii\db\ActiveRecord $model */
\kovenant\seo\MetaTagsWidget::widget(['component' => $model]);
````

In addition to the widget settings you can configure common widget options via container definitions in your config file

Example of full options:

````php
    'container' => [
        'definitions' => [
            'kovenant\seo\MetaTagsWidget' => [
                // set view attributes
                'viewH1Attribute' => 'h1', /* use <h1><?= $this->h1 ?></h1> in your view/layout */
                'viewTitleAttribute' => 'title', /* will produce <title> */

                // Set the model attributes
                'componentNameAttribute' => 'name', // default name attribute (e.g. for link name)
                'componentH1Attribute' => 'h1', // h1 for page
                'componentTitleAttribute' => 'title', // meta title
                'componentKeywordsAttribute' => 'keywords', // meta keywords
                'componentDescriptionAttribute' => 'description', // meta description

                // set pager params
                'pageText' => 'Страница', // page text [Page for default]
                'pageParam' => 'page', // get param for current page

                // In all templates placeholder {text} is an original value
                'templateH1' => '{text}',
                'templateTitle' => '{text}{pager} | {appName}', // {appName} will add name of application
                'templateKeywords' => '{text}',
                'templateDescription' => '{text}{pager}', // {pager} will be replaced with text about current page
                'templatePager' => ' - {pageText} {pageValue}', // template for such replacement

                // method from \kovenant\seo\SeoModelBehavior that will return absolute url for the page of this record
                'absoluteUrlMethod' => 'getAbsoluteUrl'
            ]
        ],
    ],
    'view' => [
        //you can use custom view for h1 support
        'class' => 'kovenant\seo\SeoView',
    ],
````

`componentNameAttribute` is required. Other component attributes are optional. 

The text for default value of title and description is `componentNameAttribute`.

If `viewH1Attribute` and `componentH1Attribute` are set they will be used as a default value.

`componentTitleAttribute`, `componentKeywordsAttribute` and `componentDescriptionAttribute` will set corresponding meta tags.

i.e. default value = `componentNameAttribute` << `componentH1Attribute` << `componentTitleAttribute`

---

If you set `absoluteUrlMethod` from SeoModelBehavior and current page of widget != absolute url from model, canonical link tag will be added.

---

You can inherit from MetaTagsWidget and add your own getters to use in templates, like {appName} and {pager}

---

See more examples of usage in tests.