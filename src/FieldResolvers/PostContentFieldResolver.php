<?php
namespace PoP\Posts\FieldResolvers;

use PoP\Posts\Facades\PostTypeAPIFacade;
use PoP\Posts\TypeResolvers\PostTypeResolver;
use PoP\Content\TypeAPIs\ContentEntityTypeAPIInterface;
use PoP\Content\FieldResolvers\AbstractContentEntityFieldResolver;

class PostContentFieldResolver extends AbstractContentEntityFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return array(PostTypeResolver::class);
    }

    protected function getContentEntityTypeAPI(): ContentEntityTypeAPIInterface
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI;
    }
}
