<?php
namespace PoP\Posts\Conditional\RESTAPI\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\API\Facades\FieldQueryConvertorFacade;
use PoP\Routing\RouteNatures;
use PoP\Posts\Routing\RouteNatures as PostRouteNatures;
use PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;

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
        $vars = ApplicationState::getVars();
        $ret[PostRouteNatures::POST][] = [
            'module' => [\PoP_Posts_Module_Processor_FieldDataloads::class, \PoP_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_SINGLEPOST, ['fields' => isset($vars['query']) ? $vars['query'] : self::getRESTFields()]],
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
            POP_POSTS_ROUTE_POSTS => [\PoP_Posts_Module_Processor_FieldDataloads::class, \PoP_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST, ['fields' => isset($vars['query']) ? $vars['query'] : self::getRESTFields()]],
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
