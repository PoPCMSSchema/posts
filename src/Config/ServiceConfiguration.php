<?php

declare(strict_types=1);

namespace PoPSchema\Posts\Config;

use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\Root\Component\PHPServiceConfigurationTrait;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        // Add RouteModuleProcessors to the Manager
        // Load API and RESTAPI conditional classes
        if (class_exists('\PoP\API\Component') && \PoP\API\Component::isEnabled()) {
            ContainerBuilderUtils::injectServicesIntoService(
                'route_module_processor_manager',
                'PoPSchema\\Posts\\Conditional\\API\\RouteModuleProcessors',
                'add'
            );
        }
        if (class_exists('\PoP\RESTAPI\Component') && \PoP\RESTAPI\Component::isEnabled()) {
            ContainerBuilderUtils::injectServicesIntoService(
                'route_module_processor_manager',
                'PoPSchema\\Posts\\Conditional\\RESTAPI\\RouteModuleProcessors',
                'add'
            );
        }

        // Load conditional classes
        if (class_exists('\PoPSchema\Users\Component')) {
            // Load API and RESTAPI conditional classes
            if (class_exists('\PoP\API\Component') && \PoP\API\Component::isEnabled()) {
                ContainerBuilderUtils::injectServicesIntoService(
                    'route_module_processor_manager',
                    'PoPSchema\\Posts\\Conditional\\Users\\Conditional\\API\\RouteModuleProcessors',
                    'add'
                );
            }
            if (class_exists('\PoP\RESTAPI\Component') && \PoP\RESTAPI\Component::isEnabled()) {
                ContainerBuilderUtils::injectServicesIntoService(
                    'route_module_processor_manager',
                    'PoPSchema\\Posts\\Conditional\\Users\\Conditional\\RESTAPI\\RouteModuleProcessors',
                    'add'
                );
            }
        }

        if (class_exists('\PoPSchema\Tags\Component')) {
            // Load API and RESTAPI conditional classes
            if (class_exists('\PoP\API\Component') && \PoP\API\Component::isEnabled()) {
                ContainerBuilderUtils::injectServicesIntoService(
                    'route_module_processor_manager',
                    'PoPSchema\\Posts\\Conditional\\Tags\\Conditional\\API\\RouteModuleProcessors',
                    'add'
                );
            }
            if (class_exists('\PoP\RESTAPI\Component') && \PoP\RESTAPI\Component::isEnabled()) {
                ContainerBuilderUtils::injectServicesIntoService(
                    'route_module_processor_manager',
                    'PoPSchema\\Posts\\Conditional\\Tags\\Conditional\\RESTAPI\\RouteModuleProcessors',
                    'add'
                );
            }
        }
    }
}
