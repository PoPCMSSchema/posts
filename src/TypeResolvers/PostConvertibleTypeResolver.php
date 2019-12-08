<?php
namespace PoP\Posts\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractConvertibleTypeResolver;

class PostConvertibleTypeResolver extends AbstractConvertibleTypeResolver
{
    public const DATABASE_KEY_NAME = 'convertible-posts';

    public function getConvertibleDatabaseKeyName(): string
    {
        return self::DATABASE_KEY_NAME;
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

