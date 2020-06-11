<?php

declare(strict_types=1);

namespace PoP\Posts\TypeResolverPickers\Optional;

use PoP\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoP\Posts\TypeResolverPickers\AbstractPostTypeResolverPicker;

class PostCustomPostTypeResolverPicker extends AbstractPostTypeResolverPicker
{
    public static function getClassesToAttachTo(): array
    {
        return [
            CustomPostUnionTypeResolver::class,
        ];
    }
}
