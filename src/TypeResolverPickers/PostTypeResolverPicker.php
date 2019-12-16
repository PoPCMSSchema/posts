<?php
namespace PoP\Posts\TypeResolverPickers;

use PoP\ComponentModel\TypeResolverPickers\AbstractTypeResolverPicker;
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

    public function getSchemaDefinitionObjectNature(): string
    {
        return 'is-post';
    }

    public function getTypeResolverClass(): string
    {
        return PostTypeResolver::class;
    }

    public function process($resultItemOrID): bool
    {
        $cmspostsapi = \PoP\Posts\FunctionAPIFactory::getInstance();
        $cmspostsresolver = \PoP\Posts\ObjectPropertyResolverFactory::getInstance();
        $postID = is_object($resultItemOrID) ? $cmspostsresolver->getPostId($resultItemOrID) : $resultItemOrID;
        return $cmspostsapi->getPostType($postID) == 'post';
    }
}
