<?php

declare(strict_types=1);

namespace PoP\Posts\FieldResolvers;

use PoP\Posts\Facades\PostTypeAPIFacade;
use PoP\Posts\TypeResolvers\PostTypeResolver;
use PoP\Content\TypeAPIs\CustomPostTypeAPIInterface;
use PoP\Content\FieldResolvers\AbstractCustomPostFieldResolver;

class PostContentFieldResolver extends AbstractCustomPostFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return [
            PostTypeResolver::class,
        ];
    }

    protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI;
    }
}
