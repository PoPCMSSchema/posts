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
}
