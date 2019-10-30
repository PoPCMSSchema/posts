<?php
namespace PoP\Posts\FieldValueResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\API\FieldResolver_Root;

class RootPostFieldValueResolver extends AbstractPostFieldValueResolver
{
    use PostFieldValueResolverTrait;

    public static function getClassesToAttachTo(): array
    {
        return array(FieldResolver_Root::class);
    }

    public function getFieldDocumentationDescription(FieldResolverInterface $fieldResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'post' => $translationAPI->__('ID of the post', 'pop-posts'),
			'posts' => $translationAPI->__('IDs of the posts in the current site', 'pop-posts'),
        ];
        return $descriptions[$fieldName] ?? parent::getFieldDocumentationDescription($fieldResolver, $fieldName);
    }
}
