<?php
namespace PoP\Posts\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractConvertibleTypeResolver;

class PostConvertibleTypeResolver extends AbstractConvertibleTypeResolver
{
    public const DATABASE_KEY = 'convertible-posts';

    public function getDatabaseKey(): string
    {
        return self::DATABASE_KEY;
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

