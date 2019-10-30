<?php
namespace PoP\Posts\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractFieldResolver;

class PostFieldResolver extends AbstractFieldResolver
{
    public function getId($resultItem)
    {
        $cmspostsresolver = \PoP\Posts\ObjectPropertyResolverFactory::getInstance();
        $post = $resultItem;
        return $cmspostsresolver->getPostId($post);
    }

    public function getIdFieldDataloaderClass()
    {
        return \PoP\Posts\Dataloader_ConvertiblePostList::class;
    }
}

