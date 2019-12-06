<?php
namespace PoP\Posts\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

class PostTypeResolver extends AbstractTypeResolver
{
    public const DATABASE_KEY = 'posts';

    public function getDatabaseKey()
    {
        return self::DATABASE_KEY;
    }

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

