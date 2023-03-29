<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\Posts\RelationalTypeDataLoaders\ObjectType\PostObjectTypeDataLoader;

class PostObjectTypeResolver extends AbstractCustomPostObjectTypeResolver
{
    private ?PostObjectTypeDataLoader $postObjectTypeDataLoader = null;

    final public function setPostObjectTypeDataLoader(PostObjectTypeDataLoader $postObjectTypeDataLoader): void
    {
        $this->postObjectTypeDataLoader = $postObjectTypeDataLoader;
    }
    final protected function getPostObjectTypeDataLoader(): PostObjectTypeDataLoader
    {
        /** @var PostObjectTypeDataLoader */
        return $this->postObjectTypeDataLoader ??= $this->instanceManager->getInstance(PostObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'Post';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Representation of a post', 'posts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostObjectTypeDataLoader();
    }
}
