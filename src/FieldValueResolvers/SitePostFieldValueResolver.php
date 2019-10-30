<?php
namespace PoP\Posts\FieldValueResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Engine\FieldValueResolvers\SiteFieldValueResolverTrait;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\API\FieldResolver_Sites;

class SitePostFieldValueResolver extends AbstractPostFieldValueResolver
{
    use PostFieldValueResolverTrait, SiteFieldValueResolverTrait;

    public static function getClassesToAttachTo(): array
    {
        return array(FieldResolver_Sites::class);
    }

    public function getFieldDocumentationDescription(FieldResolverInterface $fieldResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'post' => $translationAPI->__('ID of the post', 'pop-posts'),
			'posts' => $translationAPI->__('IDs of the posts in the site', 'pop-posts'),
        ];
        return $descriptions[$fieldName] ?? parent::getFieldDocumentationDescription($fieldResolver, $fieldName);
    }
}
