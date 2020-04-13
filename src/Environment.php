<?php

declare(strict_types=1);

namespace PoP\Posts;

class Environment
{
    public static function addPostTypeToContentEntityUnionTypes(): bool
    {
        return isset($_ENV['ADD_POST_TYPE_TO_CONTENTENTITY_UNION_TYPES']) ? strtolower($_ENV['ADD_POST_TYPE_TO_CONTENTENTITY_UNION_TYPES']) == "true" : true;
    }
}
