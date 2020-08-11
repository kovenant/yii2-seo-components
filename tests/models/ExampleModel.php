<?php

namespace kovenant\seo\tests\models;

use kovenant\seo\SeoModelBehavior;
use yii\base\Model;

/**
 * Class ExampleModel
 * @package kovenant\seo\tests\models
 * @author Anton Berezin <kovenant.rus@gmail.com>
 *
 * @mixin SeoModelBehavior
 */
class ExampleModel extends Model
{
    /**
     * Attributes instead database values
     * @var string
     */
    public $alias = 'alias-value';
    public $name = 'Name value';
    public $description = 'Example description';
    public $title = 'Meta title';
    public $keywords = 'Meta keywords';
    public $h1 = 'H1 tag value';

    public function behaviors()
    {
        return [
            'seo' => [
                'class' => SeoModelBehavior::class,
                'route' => ['/example/action', 'alias' => '{alias}']
            ]
        ];
    }
}