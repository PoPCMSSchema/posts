<?php

declare(strict_types=1);

namespace PoP\Posts;

use PoP\Posts\Environment;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use PoP\Posts\Config\ServiceConfiguration;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\Posts\TypeResolverPickers\Optional\PostContentEntityTypeResolverPicker;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use YAMLServicesTrait;
    // const VERSION = '0.1.0';

    public static function getDependedComponentClasses(): array
    {
        return [
            \PoP\QueriedObject\Component::class,
            \PoP\Content\Component::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     *
     * @return array
     */
    public static function getDependedConditionalComponentClasses(): array
    {
        return [
            \PoP\API\Component::class,
            \PoP\RESTAPI\Component::class,
        ];
    }

    public static function getDependedMigrationPlugins(): array
    {
        return [
            'migrate-posts',
        ];
    }

    /**
     * Initialize services
     */
    protected static function doInitialize(array $configuration = [], bool $skipSchema = false): void
    {
        parent::doInitialize($configuration, $skipSchema);
        ComponentConfiguration::setConfiguration($configuration);
        self::initYAMLServices(dirname(__DIR__));
        self::maybeInitYAMLSchemaServices(dirname(__DIR__), $skipSchema);
        ServiceConfiguration::initialize();
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot(): void
    {
        parent::beforeBoot();

        // Initialize classes
        ContainerBuilderUtils::registerTypeResolversFromNamespace(__NAMESPACE__ . '\\TypeResolvers');
        ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__ . '\\FieldResolvers');
        self::attachTypeResolverPickers();
    }

    /**
     * If enabled, load the TypeResolverPickers
     *
     * @return void
     */
    protected static function attachTypeResolverPickers()
    {
        if (Environment::addPostTypeToContentEntityUnionTypes()) {
            PostContentEntityTypeResolverPicker::attach(AttachableExtensionGroups::TYPERESOLVERPICKERS);
        }
    }
}
