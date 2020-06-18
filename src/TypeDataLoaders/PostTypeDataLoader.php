<?php

declare(strict_types=1);

namespace PoP\Posts\TypeDataLoaders;

use PoP\Posts\Facades\PostTypeAPIFacade;
use PoP\CustomPosts\TypeDataLoaders\CustomPostTypeDataLoader;

class PostTypeDataLoader extends CustomPostTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        $query = $this->getObjectQuery($ids);
        return $postTypeAPI->getPosts($query);
    }

    public function executeQuery($query, array $options = [])
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI->getPosts($query, $options);
    }
}
