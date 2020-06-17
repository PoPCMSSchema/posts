<?php

declare(strict_types=1);

namespace PoP\Posts\Conditional\RESTAPI\RouteModuleProcessors;

use PoP\Routing\RouteNatures;
use PoP\ComponentModel\State\ApplicationState;
use PoP\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;
use PoP\CustomPosts\Conditional\RESTAPI\RouteModuleProcessors\EntryRouteModuleProcessorHelpers;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    public function getModulesVarsPropertiesByNature()
    {
        $ret = array();
        $vars = ApplicationState::getVars();
        $ret[CustomPostRouteNatures::CUSTOMPOST][] = [
            'module' => [
                \PoP_Posts_Module_Processor_FieldDataloads::class,
                \PoP_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEPOST,
                [
                    'fields' => isset($vars['query']) ?
                        $vars['query'] :
                        EntryRouteModuleProcessorHelpers::getRESTFields()
                    ]
                ],
            'conditions' => [
                'scheme' => POP_SCHEME_API,
                'datastructure' => RESTDataStructureFormatter::getName(),
            ],
        ];

        return $ret;
    }

    public function getModulesVarsPropertiesByNatureAndRoute()
    {
        $ret = array();
        $vars = ApplicationState::getVars();
        $routemodules = array(
            POP_POSTS_ROUTE_POSTS => [
                \PoP_Posts_Module_Processor_FieldDataloads::class,
                \PoP_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST,
                [
                    'fields' => isset($vars['query']) ?
                        $vars['query'] :
                        EntryRouteModuleProcessorHelpers::getRESTFields()
                    ]
                ],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => POP_SCHEME_API,
                    'datastructure' => RESTDataStructureFormatter::getName(),
                ],
            ];
        }

        return $ret;
    }
}
