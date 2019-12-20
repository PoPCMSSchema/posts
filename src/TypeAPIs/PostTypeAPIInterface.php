<?php
namespace PoP\Posts\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface PostTypeAPIInterface
{
    /**
     * Return the post's ID
     *
     * @param [type] $post
     * @return void
     */
    public function getID($post);
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
    public function getStatus($postObjectOrID): ?string;
    public function getPosts($query, array $options = []);
    public function getPostTypes($query = array()): array;
    public function getPostType($post);
    public function getPermalink($postObjectOrID): ?string;
    public function getExcerpt($postObjectOrID): ?string;
    public function getTitle($postObjectOrID): ?string;
    // public function getSinglePostTitle($post);
    public function getSlug($postObjectOrID): ?string;
    public function getContent($postObjectOrID): ?string;
    public function getBasicPostContent($post_id);
    public function getPostCount($query);
    public function getExcerptMore();
    public function getExcerptLength();
    public function getPublishedDate($postObjectOrID): ?string;
    public function getAuthorID($postObjectOrID);
    public function getModifiedDate($postObjectOrID): ?string;
}
