<?php

namespace kovenant\seo\tests;

use kovenant\seo\MetaTagsWidget;
use kovenant\seo\SeoView;
use kovenant\seo\tests\models\ExampleModel;
use PHPUnit\Framework\TestCase;
use yii\base\InvalidConfigException;

/**
 * Class MetaTagsWidgetTest
 * @package kovenant\seo\tests
 * @author Anton Berezin <kovenant.rus@gmail.com>
 */
class MetaTagsWidgetTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $requestMock;

    protected function setUp()
    {
        $this->requestMock = $this
            ->getMockBuilder('\yii\web\Request')
            ->getMock();

        \Yii::$app->set('request', $this->requestMock);
    }

    public function testEmptyParamsException()
    {
        $this->expectException(InvalidConfigException::class);
        new MetaTagsWidget();
    }

    public function testBadComponentException()
    {
        $this->expectException(InvalidConfigException::class);
        new MetaTagsWidget(['component' => (object)['attribute' => 'value']]);
    }

    public function testEmptyComponentNameAttributeException()
    {
        $this->expectException(InvalidConfigException::class);
        new MetaTagsWidget(['component' => new ExampleModel(), 'componentNameAttribute' => '']);
    }

    public function testMinimalConfig()
    {
        $widget = new MetaTagsWidget(['component' => new ExampleModel()]);
        $widget->run();

        /** @var SeoView $view */
        $view = $widget->view;

        self::assertSame('Name value | SeoApp', $view->title);
        self::assertSame(['description' => '<meta name="description" content="Name value">'], $view->metaTags);
    }

    public function testPage()
    {
        $this->requestMock->method('getQueryParam')
            ->with('page', 0)
            ->willReturn(2);

        $widget = new MetaTagsWidget(['component' => new ExampleModel()]);
        $widget->run();

        /** @var SeoView $view */
        $view = $widget->view;

        self::assertSame('Name value - Page 2 | SeoApp', $view->title);
        self::assertSame(['description' => '<meta name="description" content="Name value - Page 2">'], $view->metaTags);
    }

    public function testTemplateTitleNotExistsField()
    {
        $widget = new MetaTagsWidget(['component' => new ExampleModel(), 'templateTitle' => '{text} - {notExistsField}']);
        $widget->run();

        /** @var SeoView $view */
        $view = $widget->view;

        self::assertSame('Name value - {notExistsField}', $view->title);
    }

    public function testTemplateTitleNotStringField()
    {
        $widget = new MetaTagsWidget(['component' => new ExampleModel(), 'templateTitle' => '{text}: {notStringField}']);
        $widget->run();

        /** @var SeoView $view */
        $view = $widget->view;

        self::assertSame('Name value: {notStringField}', $view->title);
    }

    public function testFullConfig()
    {
        $this->requestMock
            ->method('getQueryParam')
            ->with('current-page', 0)
            ->willReturn(3);

        $widget = new MetaTagsWidget([
            'viewH1Attribute' => 'h1',
            'componentH1Attribute' => 'h1',
            'componentTitleAttribute' => 'title',
            'componentKeywordsAttribute' => 'keywords',
            'componentDescriptionAttribute' => 'description',
            'templateTitle' => '{text}{pager} // {appName}',
            'templateH1' => '{text} of {appName}',
            'templateKeywords' => '{text}; seo; test',
            'templateDescription' => '{appName}: {text}',
            'templatePager' => ' \ {pageText}: {pageValue}',
            'pageText' => 'Страница',
            'pageParam' => 'current-page',
            'component' => new ExampleModel()
        ]);
        $widget->run();

        /** @var SeoView $view */
        $view = $widget->view;

        self::assertSame('Meta title \ Страница: 3 // SeoApp', $view->title);
        self::assertSame('H1 tag value of SeoApp', $view->h1);
        self::assertSame('<meta name="keywords" content="Meta keywords; seo; test">', $view->metaTags['keywords']);
        self::assertSame('<meta name="description" content="SeoApp: Example description">', $view->metaTags['description']);
    }

    public function testCanonical()
    {
        $this->requestMock
            ->method('getAbsoluteUrl')
            ->willReturn('https://example.com/example/action?alias=alias-value');

        $widget = new MetaTagsWidget([
            'absoluteUrlMethod' => 'getAbsoluteUrl',
            'component' => new ExampleModel()
        ]);

        $widget->run();

        /** @var SeoView $view */
        $view = $widget->view;

        self::assertArrayNotHasKey('canonical', $view->linkTags);
    }

    public function testNotCanonical()
    {
        $this->requestMock
            ->method('getAbsoluteUrl')
            ->willReturn('https://example.com/example/action?alias=old-value');

        $widget = new MetaTagsWidget([
            'absoluteUrlMethod' => 'getAbsoluteUrl',
            'component' => new ExampleModel()
        ]);

        $widget->run();
        /** @var SeoView $view */
        $view = $widget->view;

        self::assertSame(
            '<link href="https://example.com/example/action?alias=alias-value" rel="canonical">',
            $view->linkTags['canonical']
        );
    }
}
