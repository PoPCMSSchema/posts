<?php
namespace PoP\Posts\FieldResolvers;

use PoP\Posts\Facades\PostTypeAPIFacade;
use PoP\Posts\TypeResolvers\PostTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\UnionTypeHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Content\TypeResolvers\ContentEntityUnionTypeResolver;
use PoP\PostsWP\TypeResolverPickers\ContentEntityUnionTypeHelpers;
use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;

abstract class AbstractPostFieldResolver extends AbstractQueryableFieldResolver
{
    public static function getFieldNamesToResolve(): array
    {
        return [
			'posts',
			'content',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
			'posts' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
			'content' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'posts' => $translationAPI->__('Posts', 'pop-posts'),
			'content' => $translationAPI->__('Collection of all types considered “content” (eg: posts and events)', 'pop-posts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'posts':
            case 'content':
                return $this->getFieldArgumentsSchemaDefinitions($typeResolver, $fieldName);
        }
        return parent::getSchemaFieldArgs($typeResolver, $fieldName);
    }

    public function enableOrderedSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        switch ($fieldName) {
            case 'posts':
            case 'content':
                return false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }

    protected function getQuery(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = []): array
    {
        switch ($fieldName) {
            case 'posts':
            case 'content':
                $query = [
                    'limit' => -1,
                    'post-status' => [
                        \POP_POSTSTATUS_PUBLISHED,
                    ],
                ];
                if ($fieldName == 'content') {
                    $query['types-from-union-resolver-class'] = ContentEntityUnionTypeResolver::class;
                }
                return $query;
        }
        return [];
    }

    public function resolveValue(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'posts':
            case 'content':
                $query = $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);
                $options = [
                    'return-type' => POP_RETURNTYPE_IDS,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $postTypeAPI->getPosts($query, $options);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        switch ($fieldName) {
            case 'posts':
                return PostTypeResolver::class;
            case 'content':
                return UnionTypeHelpers::getUnionOrTargetTypeResolverClass(ContentEntityUnionTypeResolver::class);
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName, $fieldArgs);
    }
}
