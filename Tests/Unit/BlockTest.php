<?php

namespace Oro\Component\Layout\Tests\Unit;

use Oro\Component\Layout\Block;
use Oro\Component\Layout\LayoutContext;
use Oro\Component\Layout\RawLayout;

class BlockTest extends \PHPUnit_Framework_TestCase
{
    /** @var RawLayout */
    protected $rawLayout;

    /** @var LayoutContext */
    protected $context;

    /** @var Block */
    protected $block;

    protected function setUp()
    {
        $this->rawLayout = new RawLayout();
        $this->context   = new LayoutContext();
        $this->block     = new Block(
            $this->rawLayout,
            $this->context
        );
    }

    public function testGetContext()
    {
        $this->assertSame($this->context, $this->block->getContext());
    }

    public function testInitialize()
    {
        $id = 'test_id';

        $this->block->initialize($id);

        $this->assertEquals($id, $this->block->getId());
    }

    public function testGetName()
    {
        $id   = 'test_id';
        $name = 'test_name';

        $this->rawLayout->add($id, null, $name);

        $this->block->initialize($id);

        $this->assertEquals($name, $this->block->getName());
    }

    public function testGetNameWhenBlockTypeIsAddedAsObject()
    {
        $id   = 'test_id';
        $name = 'test_name';

        $type = $this->getMock('Oro\Component\Layout\BlockTypeInterface');
        $type->expects($this->once())
            ->method('getName')
            ->will($this->returnValue($name));

        $this->rawLayout->add($id, null, $type);

        $this->block->initialize($id);

        $this->assertEquals($name, $this->block->getName());
    }

    public function testGetAliases()
    {
        $id = 'test_id';

        $this->rawLayout->add($id, null, 'test_name');
        $this->rawLayout->addAlias('alias1', $id);
        $this->rawLayout->addAlias('alias2', 'alias1');

        $this->block->initialize($id);

        $this->assertEquals(['alias1', 'alias2'], $this->block->getAliases());
    }

    public function testGetParent()
    {
        $this->rawLayout->add('root', null, 'root');
        $this->rawLayout->add('header', 'root', 'header');
        $this->rawLayout->add('logo', 'header', 'logo');

        $this->block->initialize('logo');
        $this->assertNotNull($this->block->getParent());
        $this->assertEquals('header', $this->block->getParent()->getId());
        $this->assertNotNull($this->block->getParent()->getParent());
        $this->assertEquals('root', $this->block->getParent()->getParent()->getId());
        $this->assertNull($this->block->getParent()->getParent()->getParent());

        $this->block->initialize('header');
        $this->assertNotNull($this->block->getParent());
        $this->assertEquals('root', $this->block->getParent()->getId());
        $this->assertNull($this->block->getParent()->getParent());
    }

    public function testGetOptions()
    {
        $this->rawLayout->add('root', null, 'root', ['root_option1' => 'val1']);
        $this->rawLayout->setProperty(
            'root',
            RawLayout::RESOLVED_OPTIONS,
            ['root_option1' => 'val1', 'id' => 'root']
        );
        $this->rawLayout->add('header', 'root', 'header', ['header_option1' => 'val1']);
        $this->rawLayout->setProperty(
            'header',
            RawLayout::RESOLVED_OPTIONS,
            ['header_option1' => 'val1', 'id' => 'header']
        );

        $this->block->initialize('header');

        $this->assertEquals(
            ['header_option1' => 'val1', 'id' => 'header'],
            $this->block->getOptions()
        );
        $this->assertEquals(
            ['root_option1' => 'val1', 'id' => 'root'],
            $this->block->getParent()->getOptions()
        );
    }
}
