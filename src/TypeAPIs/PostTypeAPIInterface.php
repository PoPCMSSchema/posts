<?php

declare(strict_types=1);

namespace PoP\Posts\TypeAPIs;

use PoP\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface PostTypeAPIInterface extends CustomPostTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Post
     *
     * @param [type] $object
     * @return boolean
     */
    public function isInstanceOfPostType($object): bool;
    /**
     * Get the post with provided ID or, if it doesn't exist, null
     *
     * @param [type] $id
     * @return void
     */
    public function getPost($id): ?object;
    /**
     * Indicate if an post with provided ID exists
     *
     * @param [type] $id
     * @return void
     */
    public function postExists($id): bool;

    public function getPosts($query, array $options = []): array;
    public function getPostTypes($query = array()): array;
    public function getSlug($postObjectOrID): ?string;
    public function getBasicPostContent($post_id);
    public function getPostCount(array $query = [], array $options = []): int;
    public function getExcerptMore();
    public function getExcerptLength();
}
