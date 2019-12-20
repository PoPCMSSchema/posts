<?php
namespace PoP\Posts\TypeDataLoaders;

use PoP\Posts\TypeDataLoaders\PostTypeDataLoader;
use PoP\Posts\TypeResolvers\PostUnionTypeResolver;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\TypeResolverPickers\CastableTypeResolverPickerInterface;
use PoP\Posts\Facades\PostTypeAPIFacade;

class PostUnionTypeDataLoader extends PostTypeDataLoader
{
    public function getObjectQuery(array $ids): array
    {
        $query = parent::getObjectQuery($ids);
        // From all post types
        $cmspostsapi = PostTypeAPIFacade::getInstance();
        $query['post-types'] = array_keys($cmspostsapi->getPostTypes());

        return $query;
    }

    public function getDataFromIdsQuery(array $ids): array
    {
        $query = parent::getDataFromIdsQuery($ids);

        // Allow absolutely any post type, including events and highlights
        $cmspostsapi = PostTypeAPIFacade::getInstance();
        $query['post-types'] = array_keys($cmspostsapi->getPostTypes());

        return $query;
    }

    public function getObjects(array $ids): array
    {
        $posts = parent::getObjects($ids);

        // After executing `get_posts` it returns a list of posts, without converting the object to its own post type
        // Cast the posts to their own classes (eg: event)
        $instanceManager = InstanceManagerFacade::getInstance();
        $postUnionTypeResolver = $instanceManager->getInstance(PostUnionTypeResolver::class);
        $posts = array_map(
            function($post) use($postUnionTypeResolver) {
                $targetTypeResolverPicker = $postUnionTypeResolver->getTargetTypeResolverPicker($post);
                if (is_null($targetTypeResolverPicker)) {
                    return $post;
                }
                if ($targetTypeResolverPicker instanceof CastableTypeResolverPickerInterface) {
                    // Cast object, eg: from post to event
                    return $targetTypeResolverPicker->cast($post);
                }
                return $post;
            },
            $posts
        );
        return $posts;
    }
}
