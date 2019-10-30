<?php
namespace PoP\Posts\FieldValueResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;

trait PostFieldValueResolverTrait
{
    public static function getFieldNamesToResolve(): array
    {
        return array_merge(
            parent::getFieldNamesToResolve(),
            [
                'post',
            ]
        );
    }

    public function getFieldDocumentationType(FieldResolverInterface $fieldResolver, string $fieldName): ?string
    {
        $types = [
			'post' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getFieldDocumentationType($fieldResolver, $fieldName);
    }

    public function getFieldDocumentationDescription(FieldResolverInterface $fieldResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'post' => $translationAPI->__('ID of the post', 'pop-posts'),
        ];
        return $descriptions[$fieldName] ?? parent::getFieldDocumentationDescription($fieldResolver, $fieldName);
    }

    public function getFieldDocumentationArgs(FieldResolverInterface $fieldResolver, string $fieldName): array
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'post':
                return [
                    [
                        SchemaDefinition::ARGNAME_NAME => 'id',
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The post ID', 'pop-posts'),
                        SchemaDefinition::ARGNAME_MANDATORY => true,
                    ],
                ];
        }
        return parent::getFieldDocumentationArgs($fieldResolver, $fieldName) ?? parent::getFieldDocumentationArgs($fieldResolver, $fieldName);
    }

    public function resolveValue(FieldResolverInterface $fieldResolver, $resultItem, string $fieldName, array $fieldArgs = [])
    {
        $cmspostsapi = \PoP\Posts\FunctionAPIFactory::getInstance();
        switch ($fieldName) {
            case 'post':
                $query = [
                    'include' => [$fieldArgs['id']],
                    'post-status' => [
                        POP_POSTSTATUS_PUBLISHED,
                    ],
                ];
                $options = [
                    'return-type' => POP_RETURNTYPE_IDS,
                ];
                if ($posts = $cmspostsapi->getPosts($query, $options)) {
                    return $posts[0];
                }
                return null;
        }

        return parent::resolveValue($fieldResolver, $resultItem, $fieldName, $fieldArgs);
    }

    public function resolveFieldDefaultDataloaderClass(FieldResolverInterface $fieldResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        switch ($fieldName) {
            case 'post':
                return \PoP\Posts\Dataloader_ConvertiblePostList::class;
        }

        return parent::resolveFieldDefaultDataloaderClass($fieldResolver, $fieldName, $fieldArgs);
    }
}
