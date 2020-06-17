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
     * Indicate if an post with provided ID exists
     *
     * @param [type] $id
     * @return void
     */
    public function postExists($id): bool;
    /**
     * Get the post with provided ID or, if it doesn't exist, null
     *
     * @param int $id
     * @return void
     */
    public function getPost($id): ?object;
    /**
     * Get the list of posts
     *
     * @param array $query
     * @param array $options
     * @return array
     */
    public function getPosts(array $query, array $options = []): array;
    /**
     * Get the number of posts
     *
     * @param array $query
     * @param array $options
     * @return array
     */
    public function getPostCount(array $query = [], array $options = []): int;
}
