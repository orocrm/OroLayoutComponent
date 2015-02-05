<?php

namespace Oro\Component\Layout;

interface BlockBuilderInterface
{
    /**
     * Returns the id of the block
     *
     * @return string
     */
    public function getId();

    /**
     * Returns the name of the block type
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the layout manipulator
     *
     * @return LayoutManipulatorInterface
     */
    public function getLayoutManipulator();

    /**
     * Returns the execution context
     *
     * @return ContextInterface
     */
    public function getContext();
}
