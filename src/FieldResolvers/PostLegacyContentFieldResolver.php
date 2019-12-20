<?php
namespace PoP\Posts\FieldResolvers;

use PoP\Posts\Facades\PostTypeAPIFacade;
use PoP\Posts\TypeResolvers\PostTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;

class PostLegacyContentFieldResolver extends AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return array(PostTypeResolver::class);
    }

    public static function getFieldNamesToResolve(): array
    {
        return [
            'published',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'published' => SchemaDefinition::TYPE_BOOL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'published' => $translationAPI->__('Has the post been published?', 'content'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldDeprecationDescription(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $placeholder_status = $translationAPI->__('Use \'is-status(status:%s)\' instead of \'%s\'', 'content');
        $descriptions = [
            'published' => sprintf(
                $placeholder_status,
                \POP_POSTSTATUS_PUBLISHED,
                $fieldName
            ),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDeprecationDescription($typeResolver, $fieldName, $fieldArgs);
    }

    public function resolveValue(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        $post = $resultItem;
        switch ($fieldName) {
            case 'published':
                return \POP_POSTSTATUS_PUBLISHED == $postTypeAPI->getStatus($post);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
