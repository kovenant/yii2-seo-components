<?php

define('YII_ENABLE_ERROR_HANDLER', false);
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

Yii::setAlias('@tests', __DIR__);

new \yii\console\Application([
    'id' => 'unit',
    'basePath' => __DIR__,
    'name' => 'SeoApp',
    'vendorPath' => dirname(__DIR__) . '/vendor',
    'container' => [
        'definitions' => [
            'kovenant\seo\MetaTagsWidget' => [
                'pageText' => 'Страница',
                'viewH1Attribute' => 'h1',
                'componentH1Attribute' => 'h1',
                'componentTitleAttribute' => 'title',
                'componentKeywordsAttribute' => 'keywords',
                'componentDescriptionAttribute' => 'description',
                'absoluteUrlMethod' => 'getAbsoluteUrl'
            ]
        ],
    ],
    'components' => [
        'urlManager' => [
            'baseUrl' => '',
            'hostInfo' => 'https://example.com',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'view' => [
            'class' => 'kovenant\seo\SeoView',
        ],
    ]
]);
