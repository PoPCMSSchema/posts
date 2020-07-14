<?php

declare(strict_types=1);

namespace PoP\Posts;

use PoP\Posts\Environment;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use PoP\Posts\Config\ServiceConfiguration;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\Posts\TypeResolverPickers\Optional\PostCustomPostTypeResolverPicker;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    public static $COMPONENT_DIR;

    use YAMLServicesTrait;
    // const VERSION = '0.1.0';

    public static function getDependedComponentClasses(): array
    {
        return [
            \PoP\CustomPosts\Component::class,
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
            \PoP\Taxonomies\Component::class,
            \PoP\Users\Component::class,
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
    protected static function doInitialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        parent::doInitialize($configuration, $skipSchema, $skipSchemaComponentClasses);
        ComponentConfiguration::setConfiguration($configuration);
        self::$COMPONENT_DIR = dirname(__DIR__);
        self::initYAMLServices(self::$COMPONENT_DIR);
        self::maybeInitYAMLSchemaServices(self::$COMPONENT_DIR, $skipSchema);

        if (class_exists('\PoP\Users\Component')
            && !in_array(\PoP\Users\Component::class, $skipSchemaComponentClasses)
        ) {
            \PoP\Posts\Conditional\Users\ConditionalComponent::initialize(
                $configuration,
                $skipSchema
            );
        }

        if (class_exists('\PoP\Taxonomies\Component')
            && !in_array(\PoP\Taxonomies\Component::class, $skipSchemaComponentClasses)
        ) {
            \PoP\Posts\Conditional\Taxonomies\ConditionalComponent::initialize(
                $configuration,
                $skipSchema
            );
        }

        // Initialize at the end
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

        if (class_exists('\PoP\Users\Component')) {
            \PoP\Posts\Conditional\Users\ConditionalComponent::beforeBoot();
        }

        if (class_exists('\PoP\Taxonomies\Component')) {
            \PoP\Posts\Conditional\Taxonomies\ConditionalComponent::beforeBoot();
        }
    }

    /**
     * If enabled, load the TypeResolverPickers
     *
     * @return void
     */
    protected static function attachTypeResolverPickers()
    {
        if (ComponentConfiguration::addPostTypeToCustomPostUnionTypes()
            // If $skipSchema is `true`, then services are not registered
            && !empty(ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\TypeResolverPickers'))
        ) {
            PostCustomPostTypeResolverPicker::attach(AttachableExtensionGroups::TYPERESOLVERPICKERS);
        }
    }
}
