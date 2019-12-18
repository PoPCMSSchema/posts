<?php
namespace PoP\Posts\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Posts\TypeDataLoaders\PostUnionTypeDataLoader;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\TypeResolvers\AbstractUnionTypeResolver;

class PostUnionTypeResolver extends AbstractUnionTypeResolver
{
    public const NAME = 'PostUnion';

    public function getTypeName(): string
    {
        return self::NAME;
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Union of \'post\' type resolvers', 'posts');
    }

    public function getTypeDataLoaderClass(): string
    {
        return PostUnionTypeDataLoader::class;
    }

    protected function getResultItemIDTargetTypeResolvers(array $ids): array
    {
        $resultItemIDTargetTypeResolvers = [];
        $instanceManager = InstanceManagerFacade::getInstance();
        $postUnionTypeDataLoader = $instanceManager->getInstance($this->getTypeDataLoaderClass());
        if ($posts = $postUnionTypeDataLoader->getObjects($ids)) {
            foreach ($posts as $post) {
                $typeResolverAndPicker = $this->getTypeResolverAndPicker($post);
                if (!is_null($typeResolverAndPicker)) {
                    list(
                        $targetTypeResolver,
                    ) = $typeResolverAndPicker;
                    $resultItemIDTargetTypeResolvers[$targetTypeResolver->getId($post)] = $targetTypeResolver;
                }
            }
        }
        return $resultItemIDTargetTypeResolvers;
    }
}

