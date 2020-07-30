<?php

namespace kovenant\seo\tests;

use kovenant\seo\tests\models\ExampleModel;
use PHPUnit\Framework\TestCase;

/**
 * Class SeoModelBehaviorTest
 * @package kovenant\seo\tests
 * @author Anton Berezin <kovenant.rus@gmail.com>
 */
class SeoModelBehaviorTest extends TestCase
{
    public function testGetRouteUrl()
    {
        $model = new ExampleModel();

        $routeUrl = $model->getRouteUrl();
        self::assertSame(['/example/action', 'alias' => ExampleModel::EXAMPLE_ALIAS], $routeUrl);

        $routeUrl = $model->getRouteUrl(['page' => '2']);
        self::assertSame(['/example/action', 'alias' => ExampleModel::EXAMPLE_ALIAS, 'page' => '2'], $routeUrl);

        $routeUrl = $model->getRouteUrl(['alias' => '{notExistsField}']);
        self::assertSame(['/example/action', 'alias' => '{notExistsField}'], $routeUrl);
    }

    public function testGetUrl()
    {
        $model = new ExampleModel();

        $routeUrl = $model->getUrl();
        self::assertSame('/example/action?alias=' . ExampleModel::EXAMPLE_ALIAS, $routeUrl);

        $routeUrl = $model->getUrl(['page' => '2']);
        self::assertSame('/example/action?alias=' . ExampleModel::EXAMPLE_ALIAS . '&page=2', $routeUrl);

        $params = ['alias' => '{notExistsField}'];
        $routeUrl = $model->getUrl($params);
        self::assertSame('/example/action?' . http_build_query($params), $routeUrl);
    }

    public function testGetAbsoluteUrl()
    {
        $model = new ExampleModel();
        $domain = \Yii::$app->urlManager->getHostInfo();

        $routeUrl = $model->getAbsoluteUrl();
        self::assertSame($domain . '/example/action?alias=' . ExampleModel::EXAMPLE_ALIAS, $routeUrl);

        $routeUrl = $model->getAbsoluteUrl(['page' => '2']);
        self::assertSame($domain . '/example/action?alias=' . ExampleModel::EXAMPLE_ALIAS . '&page=2', $routeUrl);

        $params = ['alias' => '{notExistsField}'];
        $routeUrl = $model->getAbsoluteUrl($params);
        self::assertSame($domain . '/example/action?' . http_build_query($params), $routeUrl);
    }
}
