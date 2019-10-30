<?php
namespace PoP\Posts\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractConvertibleFieldResolver;

class PostConvertibleFieldResolver extends AbstractConvertibleFieldResolver
{
    public function getId($resultItem)
    {
        $cmspostsresolver = \PoP\Posts\ObjectPropertyResolverFactory::getInstance();
        $post = $resultItem;
        return $cmspostsresolver->getPostId($post);
    }

    public function getIdFieldDataloaderClass()
    {
        return PostFieldResolver::class;
    }

    protected function getBaseFieldResolverClass(): string
    {
        return PostFieldResolver::class;
    }
}

