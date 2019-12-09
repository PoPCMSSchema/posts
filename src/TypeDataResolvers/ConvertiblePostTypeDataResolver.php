<?php
namespace PoP\Posts\TypeDataResolvers;

use PoP\Posts\TypeResolvers\PostConvertibleTypeResolver;
use PoP\Posts\TypeDataResolvers\PostTypeDataResolver;

class ConvertiblePostTypeDataResolver extends PostTypeDataResolver
{
    public function getTypeResolverClass(): string
    {
        return PostConvertibleTypeResolver::class;
    }
}

