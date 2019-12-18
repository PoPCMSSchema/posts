<?php
namespace PoP\Posts\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Posts\TypeDataLoaders\PostUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\AbstractUnionTypeResolver;

class PostUnionTypeResolver extends AbstractUnionTypeResolver
{
    public const NAME = 'UnionPost';

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
}

