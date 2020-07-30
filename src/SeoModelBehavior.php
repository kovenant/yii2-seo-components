<?php

namespace kovenant\seo;

use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class SeoModelBehavior
 * @package kovenant\seo
 * @author Anton Berezin <kovenant.rus@gmail.com>
 */
class SeoModelBehavior extends Behavior
{
    /**
     * @property array GET parameters used for SEO.
     */
    public $route = [];

    /**
     * Returns the route array for this model.
     * Additional $params will be merged with route from model
     * @param array $params additional GET parameters (name=>value)
     * @return array
     */
    public function getRouteUrl(array $params = [])
    {
        $params = array_merge($this->route, $params);

        return array_map([$this, 'setPlaceholders'], $params);
    }

    /**
     * Returns the URL for this model.
     * @param array $params additional GET parameters (name=>value)
     * @return string the URL
     */
    public function getUrl(array $params = [])
    {
        return Url::toRoute($this->getRouteUrl($params));
    }

    /**
     * Returns the absolute URL for this model.
     * @param array $params additional GET parameters (name=>value)
     * @return string the URL
     */
    public function getAbsoluteUrl(array $params = [])
    {
        return Url::base(true) . $this->getUrl($params);
    }

    /**
     * Replaces all placeholders in param variable with corresponding values.
     * @param string $param
     * @return string
     * @throws \Exception
     */
    protected function setPlaceholders($param)
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;

        return preg_replace_callback('/{([^}]+)}/', static function ($matches) use ($model) {
            $name = $matches[1];
            $attribute = ArrayHelper::getValue($model, $name);

            if (is_string($attribute) || is_numeric($attribute)) {
                return $attribute;
            }

            return $matches[0];

        }, $param);
    }
}