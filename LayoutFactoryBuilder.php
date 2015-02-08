<?php

namespace Oro\Component\Layout;

class LayoutFactoryBuilder implements LayoutFactoryBuilderInterface
{
    /**
     * @var ExtensionInterface[]
     */
    private $extensions = [];

    /**
     * @var BlockTypeInterface[]
     *
     * Example:
     *  [
     *      'block_type_1' => BlockTypeInterface,
     *      'block_type_2' => BlockTypeInterface
     *  ]
     */
    private $types = [];

    /**
     * @var array of BlockTypeExtensionInterface[]
     *
     * Example:
     *  [
     *      'block_type_1' => array of BlockTypeExtensionInterface,
     *      'block_type_2' => array of BlockTypeExtensionInterface
     *  ]
     */
    private $typeExtensions = [];

    /**
     * @var array of LayoutUpdateInterface[]
     *
     * Example:
     *  [
     *      'item_1' => array of LayoutUpdateInterface,
     *      'item_2' => array of LayoutUpdateInterface
     *  ]
     */
    private $layoutUpdates = [];

    /**
     * @var LayoutRendererInterface[]
     *
     * Example:
     *  [
     *      'php'  => LayoutRendererInterface,
     *      'twig' => LayoutRendererInterface
     *  ]
     */
    private $renderers = [];

    /**
     * @var string
     */
    private $defaultRenderer;

    /**
     * {@inheritdoc}
     */
    public function addExtension(ExtensionInterface $extension)
    {
        $this->extensions[] = $extension;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addBlockType(BlockTypeInterface $type)
    {
        $this->types[$type->getName()] = $type;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addBlockTypeExtension(BlockTypeExtensionInterface $typeExtension)
    {
        $this->typeExtensions[$typeExtension->getExtendedType()][] = $typeExtension;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addLayoutUpdate($id, LayoutUpdateInterface $layoutUpdate)
    {
        $this->layoutUpdates[$id][] = $layoutUpdate;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addRenderer($name, LayoutRendererInterface $renderer)
    {
        $this->renderers[$name] = $renderer;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultRenderer($name)
    {
        $this->defaultRenderer = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLayoutFactory()
    {
        // initialize extension manager
        $extensionManager = new ExtensionManager();
        foreach ($this->extensions as $extension) {
            $extensionManager->addExtension($extension);
        }
        if (!empty($this->types) || !empty($this->typeExtensions) || !empty($this->layoutUpdates)) {
            $extensionManager->addExtension(
                new PreloadedExtension(
                    $this->types,
                    $this->typeExtensions,
                    $this->layoutUpdates
                )
            );
        }

        // initialize renderer registry
        $rendererRegistry = new LayoutRendererRegistry();
        $defaultRenderer  = $this->defaultRenderer;
        foreach ($this->renderers as $name => $renderer) {
            $rendererRegistry->addRenderer($name, $renderer);
            if (!$defaultRenderer) {
                $defaultRenderer = $name;
            }
        }
        if ($defaultRenderer) {
            $rendererRegistry->setDefaultRenderer($defaultRenderer);
        }

        return new LayoutFactory($extensionManager, $rendererRegistry);
    }
}
