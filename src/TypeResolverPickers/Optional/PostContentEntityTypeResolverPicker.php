<?php

declare(strict_types=1);

namespace PoP\Posts\TypeResolverPickers\Optional;

use PoP\Content\TypeResolvers\ContentEntityUnionTypeResolver;
use PoP\Posts\TypeResolverPickers\AbstractPostTypeResolverPicker;

class PostContentEntityTypeResolverPicker extends AbstractPostTypeResolverPicker
{
    public static function getClassesToAttachTo(): array
    {
        return [
            ContentEntityUnionTypeResolver::class,
        ];
    }
}
