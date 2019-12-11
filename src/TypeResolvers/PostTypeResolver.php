<?php
namespace PoP\Posts\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\Posts\TypeDataResolvers\PostTypeDataResolver;

class PostTypeResolver extends AbstractTypeResolver
{
    public const NAME = 'Post';

    public function getTypeName(): string
    {
        return self::NAME;
    }

    public function getId($resultItem)
    {
        $cmspostsresolver = \PoP\Posts\ObjectPropertyResolverFactory::getInstance();
        $post = $resultItem;
        return $cmspostsresolver->getPostId($post);
    }

    public function getTypeDataResolverClass(): string
    {
        return PostTypeDataResolver::class;
    }
}

