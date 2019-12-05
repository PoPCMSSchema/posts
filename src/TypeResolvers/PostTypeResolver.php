<?php
namespace PoP\Posts\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

class PostTypeResolver extends AbstractTypeResolver
{
    public function getId($resultItem)
    {
        $cmspostsresolver = \PoP\Posts\ObjectPropertyResolverFactory::getInstance();
        $post = $resultItem;
        return $cmspostsresolver->getPostId($post);
    }

    public function getIdFieldTypeDataResolverClass()
    {
        return \PoP\Posts\Dataloader_ConvertiblePostList::class;
    }
}

