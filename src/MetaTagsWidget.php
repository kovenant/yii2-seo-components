<?php

namespace kovenant\seo;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\web\View;

/**
 * Class MetaTagsWidget
 * @package kovenant\seo
 * @author Anton Berezin <kovenant.rus@gmail.com>
 */
class MetaTagsWidget extends Widget
{
    /** @var Component */
    public $component;

    /** @var string */
    public $templateH1 = '{text}';
    /** @var string */
    public $templateTitle = '{text}{pager} | {appName}';
    /** @var string */
    public $templateKeywords = '{text}';
    /** @var string */
    public $templateDescription = '{text}{pager}';
    /** @var string */
    public $templatePager = ' - {pageText} {pageValue}';
    /** @var string */
    public $pageParam = 'page';
    /** @var string */
    public $pageText = 'Page';

    /**
     * @var array
     */
    public $metaTags = [];

    /** @var string */
    public $componentNameAttribute = 'name';

    /** @var string */
    public $viewH1Attribute;
    /** @var string */
    public $componentH1Attribute;

    /** @var string */
    public $viewTitleAttribute = 'title';
    /** @var string */
    public $componentTitleAttribute;

    /** @var string */
    public $componentKeywordsAttribute;

    /** @var string */
    public $componentDescriptionAttribute;

    /** @var string */
    public $absoluteUrlMethod;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->component instanceof Component) {
            throw new InvalidConfigException('component attribute must be instance of yii\base\Component');
        }

        if (empty($this->componentNameAttribute)) {
            throw new InvalidConfigException('componentNameAttribute is required');
        }

        parent::init();
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function run()
    {
        $view = $this->view;
        $component = $this->component;

        $defaultTitle = $component->{$this->componentNameAttribute};

        if ($this->viewH1Attribute) {
            if (!empty($this->componentH1Attribute) && !empty($component->{$this->componentH1Attribute})) {
                $defaultTitle = $component->{$this->componentH1Attribute};
            }

            $view->{$this->viewH1Attribute} = $this->setPlaceholders($defaultTitle, $this->templateH1);
        }

        if (!empty($this->componentTitleAttribute) && !empty($component->{$this->componentTitleAttribute})) {
            $defaultTitle = $component->{$this->componentTitleAttribute};
        }

        $view->{$this->viewTitleAttribute} = $this->setPlaceholders($defaultTitle, $this->templateTitle);

        $this->setKeywords($component);
        $this->setDescription($defaultTitle, $component);
        $this->registerMetaTags($view);
        $this->registerCanonical($component, $view);
    }

    /**
     * @param string $content
     * @param string $template
     * @return string
     * @throws \Exception
     */
    protected function setPlaceholders($content, $template)
    {
        $content = str_replace('{text}', $content, $template);

        return preg_replace_callback('/{([^}]+)}/', function ($matches) {
            $name = $matches[1];

            try {
                $attribute = ArrayHelper::getValue($this, $name);

                if (is_string($attribute) || is_numeric($attribute)) {
                    return $attribute;
                }
            } catch (\yii\base\UnknownPropertyException $e) {
                Yii::error($e);
            }

            return $matches[0];

        }, $content);
    }

    /**
     * @param Component $component
     * @param View $view
     */
    protected function registerCanonical(Component $component, View $view)
    {
        if ($this->absoluteUrlMethod) {
            $url = Yii::$app->request->getAbsoluteUrl();

            if ($url !== $component->{$this->absoluteUrlMethod}()) {
                $view->registerLinkTag([
                    'rel' => 'canonical',
                    'href' => $component->{$this->absoluteUrlMethod}()
                ], 'canonical');
            }
        }
    }

    /**
     * @param View $view
     */
    protected function registerMetaTags(View $view)
    {
        foreach ($this->metaTags as $name => $content) {
            $view->registerMetaTag([
                'name' => $name,
                'content' => $content
            ], $name);
        }
    }

    /**
     * @param Component $component
     * @throws \Exception
     */
    protected function setKeywords(Component $component)
    {
        if (!empty($this->componentKeywordsAttribute) && !empty($component->{$this->componentKeywordsAttribute})) {
            $keywords = $component->{$this->componentKeywordsAttribute};

            $this->metaTags['keywords'] = $this->setPlaceholders($keywords, $this->templateKeywords);
        }
    }

    /**
     * @param $defaultTitle
     * @param Component $component
     * @throws \Exception
     */
    protected function setDescription($defaultTitle, Component $component)
    {
        if (!empty($this->componentDescriptionAttribute)) {
            $metaDescription = $component->{$this->componentDescriptionAttribute};
        }

        if (empty($metaDescription)) {
            $metaDescription = $defaultTitle;
        }

        $this->metaTags['description'] = $this->setPlaceholders($metaDescription, $this->templateDescription);
    }

    /**
     * Use {pager} in templates
     * @return string
     */
    protected function getPager()
    {
        $pageValue = (int)Yii::$app->request->getQueryParam($this->pageParam, 0);

        if ($pageValue === 0) {
            return '';
        }

        return str_replace([
            '{pageText}',
            '{pageValue}',
        ], [
            $this->pageText,
            $pageValue
        ], $this->templatePager);
    }

    /**
     * Use {appName} in templates
     * @return string
     */
    protected function getAppName()
    {
        return Yii::$app->name;
    }
}