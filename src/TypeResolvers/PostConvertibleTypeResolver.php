<?php
namespace PoP\Posts\TypeResolvers;

use PoP\Posts\TypeDataLoaders\PostTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\AbstractConvertibleTypeResolver;

class PostConvertibleTypeResolver extends AbstractConvertibleTypeResolver
{
    public const DATABASE_KEY_NAME = 'convertible-posts';

    public function getConvertibleTypeCollectionName(): string
    {
        return self::DATABASE_KEY_NAME;
    }

    // protected function getBaseTypeResolverClass(): string
    // {
    //     return PostTypeResolver::class;
    // }

    public function getTypeDataLoaderClass(): string
    {
        return PostTypeDataLoader::class;
    }
}

