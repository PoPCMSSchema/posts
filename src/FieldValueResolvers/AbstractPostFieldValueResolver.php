<?php
namespace PoP\Posts\FieldValueResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldValueResolvers\AbstractDBDataFieldValueResolver;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;

abstract class AbstractPostFieldValueResolver extends AbstractDBDataFieldValueResolver
{
    public static function getFieldNamesToResolve(): array
    {
        return [
			'posts',
        ];
    }

    public function getSchemaFieldType(FieldResolverInterface $fieldResolver, string $fieldName): ?string
    {
        $types = [
			'posts' => \PoP\ComponentModel\DataloadUtils::combineTypes(SchemaDefinition::TYPE_ARRAY, SchemaDefinition::TYPE_ID),
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($fieldResolver, $fieldName);
    }

    public function getSchemaFieldDescription(FieldResolverInterface $fieldResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'posts' => $translationAPI->__('IDs of the posts', 'pop-posts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($fieldResolver, $fieldName);
    }

    public function getSchemaFieldArgs(FieldResolverInterface $fieldResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'posts':
                return $this->getFieldArgumentsDocumentation($fieldResolver, $fieldName);
        }
        return parent::getSchemaFieldArgs($fieldResolver, $fieldName);
    }

    public function enableOrderedFieldDocumentationArgs(FieldResolverInterface $fieldResolver, string $fieldName): bool
    {
        switch ($fieldName) {
            case 'posts':
                return false;
        }
        return parent::enableOrderedFieldDocumentationArgs($fieldResolver, $fieldName);
    }

    protected function getQuery(FieldResolverInterface $fieldResolver, $resultItem, string $fieldName, array $fieldArgs = []): array
    {
        // $cmspostsapi = \PoP\Posts\FunctionAPIFactory::getInstance();
        switch ($fieldName) {
            case 'posts':
                return [
                    'limit' => -1,
                    'post-status' => [
                        POP_POSTSTATUS_PUBLISHED,
                    ],
                    // 'post-types' => array_keys($cmspostsapi->getPostTypes()),
                ];
        }
        return [];
    }

    public function resolveValue(FieldResolverInterface $fieldResolver, $resultItem, string $fieldName, array $fieldArgs = [])
    {
        $cmspostsapi = \PoP\Posts\FunctionAPIFactory::getInstance();
        switch ($fieldName) {
            case 'posts':
                $query = $this->getQuery($fieldResolver, $resultItem, $fieldName, $fieldArgs);
                $options = [
                    'return-type' => POP_RETURNTYPE_IDS,
                ];
                $this->addFilterDataloadQueryArgs($options, $fieldResolver, $fieldName, $fieldArgs);
                return $cmspostsapi->getPosts($query, $options);
        }

        return parent::resolveValue($fieldResolver, $resultItem, $fieldName, $fieldArgs);
    }

    public function resolveFieldDefaultDataloaderClass(FieldResolverInterface $fieldResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        switch ($fieldName) {
            case 'posts':
                return \PoP\Posts\Dataloader_ConvertiblePostList::class;
        }

        return parent::resolveFieldDefaultDataloaderClass($fieldResolver, $fieldName, $fieldArgs);
    }
}
