<?php
namespace PoP\Posts\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractConvertibleTypeResolver;
use PoP\Posts\TypeDataResolvers\ConvertiblePostTypeDataResolver;

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

    public function getTypeDataResolverClass(): string
    {
        return ConvertiblePostTypeDataResolver::class;
    }
}

