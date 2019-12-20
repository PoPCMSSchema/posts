<?php
namespace PoP\Posts\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface PostTypeAPIInterface
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
    public function getPost($id);
    /**
     * Indicate if an post with provided ID exists
     *
     * @param [type] $id
     * @return void
     */
    public function postExists($id): bool;

    // Posts
    public function getPostStatus($post_id);
    public function getPosts($query, array $options = []);
    public function getPostTypes($query = array()): array;
    public function getPostType($post);
    public function getPermalink($post_id);
    public function getExcerpt($post_id);
    public function getTitle($post_id);
    // public function getSinglePostTitle($post);
    public function getPostSlug($post_id);
    public function getPostTitle($post_id);
    public function getPostContent($post_id);
    public function getBasicPostContent($post_id);
    public function getPostCount($query);
    public function getExcerptMore();
    public function getExcerptLength();
}
