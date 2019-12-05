<?php
namespace PoP\Posts\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractConvertibleTypeResolver;

class PostConvertibleTypeResolver extends AbstractConvertibleTypeResolver
{
    public function getId($resultItem)
    {
        $cmspostsresolver = \PoP\Posts\ObjectPropertyResolverFactory::getInstance();
        $post = $resultItem;
        return $cmspostsresolver->getPostId($post);
    }

    public function getIdFieldTypeDataResolverClass()
    {
        return PostTypeResolver::class;
    }

    protected function getBaseTypeResolverClass(): string
    {
        return PostTypeResolver::class;
    }
}

