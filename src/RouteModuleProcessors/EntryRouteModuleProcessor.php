<?php
namespace PoP\Posts\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\ComponentModel\Server\Utils;
use PoP\ComponentModel\Engine_Vars;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\API\Facades\FieldQueryConvertorFacade;
use PoP\Routing\RouteNatures;
use PoP\Posts\Routing\RouteNatures as PostRouteNatures;

class EntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    private static $restFieldsQuery;
    private static $restFields;
    public static function getRESTFields(): array
    {
        if (is_null(self::$restFields)) {
            self::$restFields = self::getRESTFieldsQuery();
            if (is_string(self::$restFields)) {
                self::$restFields = FieldQueryConvertorFacade::getInstance()->convertAPIQuery(self::$restFields);
            }
        }
        return self::$restFields;
    }
    public static function getRESTFieldsQuery(): string
    {
        if (is_null(self::$restFieldsQuery)) {
            self::$restFieldsQuery = (string) HooksAPIFacade::getInstance()->applyFilters(
                'Posts:RESTFields',
                'id|title|date|url|content'
            );
        }
        return self::$restFieldsQuery;
    }

    public function getModulesVarsPropertiesByNature()
    {
        $ret = array();

        // API
        if (!Utils::disableAPI()) {

            $vars = Engine_Vars::getVars();

            // Single
            $ret[PostRouteNatures::POST][] = [
                'module' => [\PoP_Posts_Module_Processor_FieldDataloads::class, \PoP_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_DATAQUERY_SINGLEPOST_FIELDS],
                'conditions' => [
                    'scheme' => POP_SCHEME_API,
                ],
            ];
            // REST API Single
            $ret[PostRouteNatures::POST][] = [
                'module' => [\PoP_Posts_Module_Processor_FieldDataloads::class, \PoP_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_DATAQUERY_SINGLEPOST_FIELDS, ['fields' => isset($vars['query']) ? $vars['query'] : self::getRESTFields()]],
                'conditions' => [
                    'scheme' => POP_SCHEME_API,
                    'datastructure' => GD_DATALOAD_DATASTRUCTURE_REST,
                ],
            ];
        }

        return $ret;
    }

    public function getModulesVarsPropertiesByNatureAndRoute()
    {
        $ret = array();

        // API
        if (!Utils::disableAPI()) {

            $vars = Engine_Vars::getVars();

            // Page
            $routemodules = array(
                POP_POSTS_ROUTE_POSTS => [\PoP_Posts_Module_Processor_FieldDataloads::class, \PoP_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_DATAQUERY_POSTLIST_FIELDS],
            );
            foreach ($routemodules as $route => $module) {
                $ret[RouteNatures::STANDARD][$route][] = [
                    'module' => $module,
                    'conditions' => [
                        'scheme' => POP_SCHEME_API,
                    ],
                ];
            }

            // REST API Page
            $routemodules = array(
                POP_POSTS_ROUTE_POSTS => [\PoP_Posts_Module_Processor_FieldDataloads::class, \PoP_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_DATAQUERY_POSTLIST_FIELDS, ['fields' => isset($vars['query']) ? $vars['query'] : self::getRESTFields()]],
            );
            foreach ($routemodules as $route => $module) {
                $ret[RouteNatures::STANDARD][$route][] = [
                    'module' => $module,
                    'conditions' => [
                        'scheme' => POP_SCHEME_API,
                        'datastructure' => GD_DATALOAD_DATASTRUCTURE_REST,
                    ],
                ];
            }
        }

        return $ret;
    }
}
