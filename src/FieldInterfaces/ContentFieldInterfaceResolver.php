<?php
namespace PoP\Posts\FieldInterfaces;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractSchemaFieldInterfaceResolver;

class ContentFieldInterfaceResolver extends AbstractSchemaFieldInterfaceResolver
{
    public const NAME = 'Content';
    public function getInterfaceName(): string
    {
        return self::NAME;
    }

    public static function getFieldNamesToImplement(): array
    {
        return [
            'post-type',
            'title',
            'content',
            'excerpt',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'post-type' => SchemaDefinition::TYPE_STRING,
            'title' => SchemaDefinition::TYPE_STRING,
            'content' => SchemaDefinition::TYPE_STRING,
            'excerpt' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'post-type' => $translationAPI->__('Post type', 'pop-posts'),
            'title' => $translationAPI->__('Post title', 'pop-posts'),
            'content' => $translationAPI->__('Post content', 'pop-posts'),
            'excerpt' => $translationAPI->__('Post excerpt', 'pop-posts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
}
