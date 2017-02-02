<?php

namespace Oro\Component\Layout\Tests\Unit\Extension\Theme\Model;

use Doctrine\Common\Collections\ArrayCollection;

use Oro\Component\Layout\Extension\Theme\Model\PageTemplate;
use Oro\Component\Layout\Extension\Theme\Model\Theme;

class ThemeTest extends \PHPUnit_Framework_TestCase
{
    /** @var Theme */
    protected $theme;

    protected function setUp()
    {
        $this->theme = new Theme('test');
    }

    protected function tearDown()
    {
        unset($this->theme);
    }

    public function testGetNameAndConstructor()
    {
        $this->assertEquals('test', $this->theme->getName());
    }

    public function testDirectoryMethods()
    {
        $this->assertNotEmpty($this->theme->getDirectory());
        $this->assertEquals('test', $this->theme->getDirectory());

        $this->theme->setDirectory('base');
        $this->assertEquals('base', $this->theme->getDirectory());
    }

    public function testLabelMethods()
    {
        $this->assertNull($this->theme->getLabel());
        $this->theme->setLabel('Oro Base theme');
        $this->assertEquals('Oro Base theme', $this->theme->getLabel());
    }

    public function testIconMethods()
    {
        $this->assertNull($this->theme->getIcon());
        $this->theme->setIcon('icon.ico');
        $this->assertEquals('icon.ico', $this->theme->getIcon());
    }

    public function testLogoMethods()
    {
        $this->assertNull($this->theme->getLogo());
        $this->theme->setLogo('logo.png');
        $this->assertEquals('logo.png', $this->theme->getLogo());
    }

    public function testScreenshotMethods()
    {
        $this->assertNull($this->theme->getScreenshot());
        $this->theme->setScreenshot('screenshot.png');
        $this->assertEquals('screenshot.png', $this->theme->getScreenshot());
    }

    public function testGroupsMethods()
    {
        $this->assertEmpty($this->theme->getGroups());

        $this->theme->setGroups(['test']);
        $this->assertSame(['test'], $this->theme->getGroups());
    }

    public function testParentThemeMethods()
    {
        $this->assertNull($this->theme->getParentTheme());

        $this->theme->setParentTheme('base');
        $this->assertEquals('base', $this->theme->getParentTheme());
    }

    public function testDescriptionMethods()
    {
        $this->assertNull($this->theme->getDescription());

        $this->theme->setDescription('test');
        $this->assertEquals('test', $this->theme->getDescription());
    }

    public function testAddPageTemplate()
    {
        $pageTemplate = new PageTemplate('Label', 'key', 'route_name');
        $this->theme->addPageTemplate($pageTemplate);
        $this->assertEquals(new ArrayCollection([$pageTemplate]), $this->theme->getPageTemplates());
    }

    public function testAddPageTemplateAlreadyExists()
    {
        $pageTemplate = new PageTemplate('Label', 'key', 'route_name');
        $this->theme->addPageTemplate($pageTemplate);
        $this->theme->addPageTemplate($pageTemplate);
        $this->assertCount(1, $this->theme->getPageTemplates());
    }

    public function testConfigMethods()
    {
        $config = [
            'key' => 'value',
        ];

        $this->assertEquals([], $this->theme->getConfig());
        $this->theme->setConfig($config);
        $this->assertEquals($config, $this->theme->getConfig());
        $this->assertEquals($config['key'], $this->theme->getConfigByKey('key'));
        $this->assertEquals('default value', $this->theme->getConfigByKey('unknown key', 'default value'));
        $this->theme->setConfigByKey('unknown key', 'unknown value');
        $this->assertEquals('unknown value', $this->theme->getConfigByKey('unknown key', 'default value'));
    }

    public function testAddPageTemplateTitle()
    {
        $this->theme->addPageTemplateTitle('some_route', 'Some title');
        $this->assertEquals('Some title', $this->theme->getPageTemplateTitle('some_route'));
    }

    public function testGetNotExistingPageTemplateTitle()
    {
        $this->theme->addPageTemplateTitle('some_route', 'Some title');
        $this->assertEquals(null, $this->theme->getPageTemplateTitle('not_existing_route'));
    }

    public function testGetPageTemplateTitles()
    {
        $expected = [
            'some_route' => 'Some route',
            'some_other_route' => 'Some other route',
        ];

        $this->theme->addPageTemplateTitle('some_route', 'Some route');
        $this->theme->addPageTemplateTitle('some_other_route', 'Some other route');
        $this->theme->addPageTemplateTitle('some_other_route', 'Some other route');
        $this->assertEquals($expected, $this->theme->getPageTemplateTitles());
    }
}
