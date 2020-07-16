<?php

declare(strict_types=1);

namespace PoP\Posts\Conditional\Taxonomies\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Posts\FieldResolvers\AbstractPostFieldResolver;
use PoP\Tags\TypeResolvers\TagTypeResolver;

class PostTagFieldResolver extends AbstractPostFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return array(TagTypeResolver::class);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'posts' => $translationAPI->__('Posts which contain this tag', 'pop-taxonomies'),
            'postCount' => $translationAPI->__('Number of posts which contain this tag', 'pop-taxonomies'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    protected function getQuery(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = []): array
    {
        $query = parent::getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);

        $tag = $resultItem;
        switch ($fieldName) {
            case 'posts':
            case 'postCount':
                $query['tag-ids'] = [$typeResolver->getID($tag)];
                break;
        }

        return $query;
    }
}
