<?php

declare(strict_types=1);

namespace PoP\Posts\Conditional\Tags;

use PoP\Posts\Component;
use PoP\Root\Component\YAMLServicesTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;

/**
 * Initialize component
 */
class ConditionalComponent
{
    use YAMLServicesTrait;

    public static function initialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        self::initYAMLServices(Component::$COMPONENT_DIR, '/Conditional/Taxonomies');
        self::maybeInitYAMLSchemaServices(Component::$COMPONENT_DIR, $skipSchema, '/Conditional/Taxonomies');
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot(): void
    {
        ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\Hooks');
        ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__ . '\\FieldResolvers');
    }
}
