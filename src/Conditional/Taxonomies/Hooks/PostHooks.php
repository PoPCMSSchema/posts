<?php

declare(strict_types=1);

namespace PoP\Posts\Conditional\Taxonomies\Hooks;

use PoP\Engine\Hooks\AbstractHookSet;
use PoP\Tags\Conditional\RESTAPI\RouteModuleProcessors\EntryRouteModuleProcessor;

class PostHooks extends AbstractHookSet
{
    const TAG_RESTFIELDS = 'posts.id|title|date|url';

    protected function init()
    {
        $this->hooksAPI->addFilter(
            EntryRouteModuleProcessor::HOOK_REST_FIELDS,
            [$this, 'getRESTFields']
        );
    }

    public function getRESTFields($restFields): string
    {
        return $restFields . ',' . self::TAG_RESTFIELDS;
    }
}
