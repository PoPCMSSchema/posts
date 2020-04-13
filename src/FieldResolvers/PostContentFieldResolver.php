<?php

declare(strict_types=1);

namespace PoP\Posts\FieldResolvers;

use PoP\Posts\Facades\PostTypeAPIFacade;
use PoP\Posts\TypeResolvers\PostTypeResolver;
use PoP\Content\TypeAPIs\ContentEntityTypeAPIInterface;
use PoP\Content\FieldResolvers\AbstractContentEntityFieldResolver;

class PostContentFieldResolver extends AbstractContentEntityFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return [
            PostTypeResolver::class,
        ];
    }

    protected function getContentEntityTypeAPI(): ContentEntityTypeAPIInterface
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI;
    }
}
