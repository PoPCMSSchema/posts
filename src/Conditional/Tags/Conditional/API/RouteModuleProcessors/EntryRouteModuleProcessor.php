<?php

declare(strict_types=1);

namespace PoP\Posts\Conditional\Tags\Conditional\API\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\Tags\Routing\RouteNatures as TagRouteNatures;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    public function getModulesVarsPropertiesByNatureAndRoute()
    {
        $ret = array();
        $routemodules = array(
            POP_POSTS_ROUTE_POSTS => [\PoP_Taxonomies_Module_Processor_FieldDataloads::class, \PoP_Taxonomies_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST],
        );
        foreach ($routemodules as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'scheme' => POP_SCHEME_API,
                ],
            ];
        }
        return $ret;
    }
}
