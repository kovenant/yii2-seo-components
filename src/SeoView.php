<?php

namespace kovenant\seo;

use yii\web\View;

/**
 * Class SeoView
 * @package kovenant\seo
 * @author Anton Berezin <kovenant.rus@gmail.com>
 */
class SeoView extends View
{
    /** @var string */
    public $h1;
    /** @var array */
    public $breadcrumbs = [];
}