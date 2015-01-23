<?php

namespace Oro\Component\Layout\Block\Type;

use Oro\Component\Layout\AbstractBlockType;

class BodyBlockType extends AbstractBlockType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'container';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'body';
    }
}
