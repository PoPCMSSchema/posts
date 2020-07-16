<?php

declare(strict_types=1);

namespace PoP\Posts\Conditional\Taxonomies\Conditional\RESTAPI\RouteModuleProcessors;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\API\Facades\FieldQueryConvertorFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\Tags\Routing\RouteNatures as TagRouteNatures;
use PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;
use PoP\CustomPosts\Conditional\RESTAPI\RouteModuleProcessors\EntryRouteModuleProcessorHelpers;

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
            // Same as for posts, but removing the tag data
            self::$restFieldsQuery = (string) HooksAPIFacade::getInstance()->applyFilters(
                'Tags:Posts:RESTFields',
                str_replace(
                    ',' . \PoP\Taxonomies\Conditional\CustomPosts\Conditional\RESTAPI\Hooks\CustomPostHooks::TAG_RESTFIELDS,
                    '',
                    EntryRouteModuleProcessorHelpers::getRESTFieldsQuery()
                )
            );
        }
        return self::$restFieldsQuery;
    }

    public function getModulesVarsPropertiesByNatureAndRoute()
    {
        $ret = array();
        $vars = ApplicationState::getVars();
        $routemodules = array(
            POP_POSTS_ROUTE_POSTS => [
                \PoP_Taxonomies_Module_Processor_FieldDataloads::class,
                \PoP_Taxonomies_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGPOSTLIST,
                [
                    'fields' => isset($vars['query']) ?
                        $vars['query'] :
                        self::getRESTFields()
                    ]
                ],
        );
        foreach ($routemodules as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
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
