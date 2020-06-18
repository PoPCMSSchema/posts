<?php

declare(strict_types=1);

namespace PoP\Posts\FieldResolvers;

use PoP\CustomPosts\Types\Status;
use PoP\Posts\ComponentConfiguration;
use PoP\Posts\Facades\PostTypeAPIFacade;
use PoP\Posts\TypeResolvers\PostTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\CustomPosts\ModuleProcessors\CustomPostRelationalFieldDataloadModuleProcessor;

abstract class AbstractPostFieldResolver extends AbstractQueryableFieldResolver
{
    public static function getFieldNamesToResolve(): array
    {
        return [
            'posts',
            'postCount',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'posts' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'postCount' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'posts',
            'postCount',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'posts' => $translationAPI->__('Posts', 'pop-posts'),
            'postCount' => $translationAPI->__('Number of posts', 'pop-posts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'posts':
            case 'postCount':
                return array_merge(
                    $schemaFieldArgs,
                    $this->getFieldArgumentsSchemaDefinitions($typeResolver, $fieldName)
                );
        }
        return $schemaFieldArgs;
    }

    public function enableOrderedSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        switch ($fieldName) {
            case 'posts':
            case 'postCount':
                return false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }

    protected function getFieldDefaultFilterDataloadingModule(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        switch ($fieldName) {
            case 'postCount':
                return [
                    CustomPostRelationalFieldDataloadModuleProcessor::class,
                    CustomPostRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTCOUNT
                ];
        }
        return parent::getFieldDefaultFilterDataloadingModule($typeResolver, $fieldName, $fieldArgs);
    }

    protected function getQuery(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = []): array
    {
        switch ($fieldName) {
            case 'posts':
                return [
                    'limit' => ComponentConfiguration::getPostListDefaultLimit(),
                    'post-status' => [
                        Status::PUBLISHED,
                    ],
                ];
            case 'postCount':
                return [
                    'post-status' => [
                        Status::PUBLISHED,
                    ],
                ];
        }
        return [];
    }

    public function resolveValue(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'posts':
                $query = $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);
                $options = [
                    'return-type' => POP_RETURNTYPE_IDS,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $postTypeAPI->getPosts($query, $options);
            case 'postCount':
                $query = $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);
                $options = [];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $postTypeAPI->getPostCount($query, $options);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        switch ($fieldName) {
            case 'posts':
                return PostTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName, $fieldArgs);
    }
}
