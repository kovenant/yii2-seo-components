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
    'vendorPath' => dirname(__DIR__) . '/vendor',
    'components' => [
        'urlManager' => [
            'baseUrl' => '',
            'hostInfo' => 'https://example.com',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ]
]);
