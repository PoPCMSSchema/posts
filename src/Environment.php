<?php

declare(strict_types=1);

namespace PoP\Posts;

class Environment
{
    public const POST_LIST_DEFAULT_LIMIT = 'POST_LIST_DEFAULT_LIMIT';
    public const POST_LIST_MAX_LIMIT = 'POST_LIST_MAX_LIMIT';

    public static function addPostTypeToCustomPostUnionTypes(): bool
    {
        return isset($_ENV['ADD_POST_TYPE_TO_CONTENTENTITY_UNION_TYPES']) ? strtolower($_ENV['ADD_POST_TYPE_TO_CONTENTENTITY_UNION_TYPES']) == "true" : true;
    }
}
