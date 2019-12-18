<?php
namespace PoP\Posts\TypeResolverPickers;

use PoP\ComponentModel\TypeResolverPickers\AbstractTypeResolverPicker;
use PoP\Posts\Facades\PostTypeAPIFacade;
use PoP\Posts\TypeResolvers\PostUnionTypeResolver;
use PoP\Posts\TypeResolvers\PostTypeResolver;

class PostTypeResolverPicker extends AbstractTypeResolverPicker
{
    public static function getClassesToAttachTo(): array
    {
        return [
            PostUnionTypeResolver::class,
        ];
    }

    public function getTypeResolverClass(): string
    {
        return PostTypeResolver::class;
    }

    public function isInstanceOfType($object): bool
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI->isInstanceOfPostType($object);
    }

    public function isIDOfType($resultItemID): bool
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI->postExists($resultItemID);
    }
}
