<?php

namespace kovenant\seo\tests\models;

use kovenant\seo\SeoModelBehavior;
use yii\base\Model;

/**
 * Class ExampleModel
 * @package kovenant\seo\tests\models
 * @author Anton Berezin <kovenant.rus@gmail.com>
 *
 * @method getRouteUrl(array $params = [])
 * @see SeoModelBehavior::getRouteUrl()
 * @method getUrl(array $params = [])
 * @see SeoModelBehavior::getUrl()
 * @method getAbsoluteUrl(array $params = [])
 * @see SeoModelBehavior::getAbsoluteUrl()
 */
class ExampleModel extends Model
{
    const EXAMPLE_ALIAS = 'test';

    public function behaviors()
    {
        return [
            'seo' => [
                'class' => SeoModelBehavior::class,
                'route' => ['/example/action', 'alias' => '{alias}']
            ]
        ];
    }

    /**
     * Example getter instead table field
     * @return string
     */
    public function getAlias()
    {
        return self::EXAMPLE_ALIAS;
    }
}