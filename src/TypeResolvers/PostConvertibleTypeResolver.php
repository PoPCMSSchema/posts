<?php
namespace PoP\Posts\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractConvertibleTypeResolver;
use PoP\Posts\TypeDataLoaders\ConvertiblePostTypeDataLoader;

class PostConvertibleTypeResolver extends AbstractConvertibleTypeResolver
{
    public const DATABASE_KEY_NAME = 'convertible-posts';

    public function getConvertibleTypeCollectionName(): string
    {
        return self::DATABASE_KEY_NAME;
    }

    protected function getBaseTypeResolverClass(): string
    {
        return PostTypeResolver::class;
    }

    public function getTypeDataLoaderClass(): string
    {
        return ConvertiblePostTypeDataLoader::class;
    }
}

