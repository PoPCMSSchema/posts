<?php
namespace PoP\Posts\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\Posts\TypeDataResolvers\PostTypeDataResolver;

class PostTypeResolver extends AbstractTypeResolver
{
    public const TYPE_COLLECTION_NAME = 'posts';

    public function getTypeCollectionName(): string
    {
        return self::TYPE_COLLECTION_NAME;
    }

    public function getId($resultItem)
    {
        $cmspostsresolver = \PoP\Posts\ObjectPropertyResolverFactory::getInstance();
        $post = $resultItem;
        return $cmspostsresolver->getPostId($post);
    }

    public function getIdFieldTypeDataResolverClass(): string
    {
        return PostTypeDataResolver::class;
    }
}

