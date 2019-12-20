<?php
namespace PoP\Posts\FieldResolvers;

use PoP\FieldQuery\FieldQueryUtils;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Posts\TypeResolvers\PostTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Content\FieldInterfaces\ContentFieldInterfaceResolver;
use PoP\Content\FieldInterfaces\LinkableFieldInterfaceResolver;
use PoP\Content\FieldInterfaces\PublishableFieldInterfaceResolver;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;

class PostFieldResolver extends AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return array(PostTypeResolver::class);
    }

    public static function getFieldNamesToResolve(): array
    {
        return [];
    }

    public static function getImplementedInterfaceClasses(): array
    {
        return [
            ContentFieldInterfaceResolver::class,
            LinkableFieldInterfaceResolver::class,
            PublishableFieldInterfaceResolver::class,
        ];
    }

    public function resolveSchemaValidationErrorDescription(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        if ($error = parent::resolveSchemaValidationErrorDescription($typeResolver, $fieldName, $fieldArgs)) {
            return $error;
        }

        // Important: The validations below can only be done if no fieldArg contains a field!
        // That is because this is a schema error, so we still don't have the $resultItem against which to resolve the field
        // For instance, this doesn't work: /?query=arrayItem(posts(),3)
        // In that case, the validation will be done inside ->resolveValue(), and will be treated as a $dbError, not a $schemaError
        if (!FieldQueryUtils::isAnyFieldArgumentValueAField($fieldArgs)) {
            $translationAPI = TranslationAPIFacade::getInstance();
            switch ($fieldName) {
                case 'is-status':
                    $status = $fieldArgs['status'];
                    if (!in_array($status, PublishableFieldInterfaceResolver::POST_STATUSES)) {
                        return sprintf(
                            $translationAPI->__('Argument \'status\' can only have these values: \'%s\'', 'pop-posts'),
                            implode($translationAPI->__('\', \''), PublishableFieldInterfaceResolver::POST_STATUSES)
                        );
                    }
                    break;
            }
        }

        return null;
    }

    public function resolveValue(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        $cmspostsresolver = \PoP\Posts\ObjectPropertyResolverFactory::getInstance();
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $cmspostsapi = \PoP\Posts\FunctionAPIFactory::getInstance();
        $post = $resultItem;
        switch ($fieldName) {
            case 'post-type':
                return $cmspostsresolver->getPostType($post);

            case 'title':
                // return HooksAPIFacade::getInstance()->applyFilters('popcms:post:title', $cmspostsresolver->getPostTitle($post), $typeResolver->getId($post));
                return $cmspostsapi->getPostTitle($typeResolver->getId($post));

            case 'content':
                $value = $cmspostsapi->getPostContent($typeResolver->getId($post));
                return HooksAPIFacade::getInstance()->applyFilters('pop_content', $value, $typeResolver->getId($post));

            case 'url':
                return $cmspostsapi->getPermalink($typeResolver->getId($post));

            case 'excerpt':
                return $cmspostsapi->getExcerpt($typeResolver->getId($post));

            case 'status':
                return $cmspostsapi->getPostStatus($typeResolver->getId($post));

            case 'is-draft':
                return \POP_POSTSTATUS_DRAFT == $cmspostsapi->getPostStatus($typeResolver->getId($post));

            case 'published':
                return \POP_POSTSTATUS_PUBLISHED == $cmspostsapi->getPostStatus($typeResolver->getId($post));

            case 'not-published':
                return !$typeResolver->resolveValue($post, 'published', $variables, $expressions, $options);

            case 'is-status':
                return $fieldArgs['status'] == $cmspostsapi->getPostStatus($typeResolver->getId($post));

            case 'date':
                $format = $fieldArgs['format'] ?? $cmsengineapi->getOption(NameResolverFacade::getInstance()->getName('popcms:option:dateFormat'));
                return $cmsengineapi->getDate($format, $cmspostsresolver->getPostDate($post));

            case 'datetime':
                // If it is the current year, don't add the year. Otherwise, do
                // 15 Jul, 21:47 or // 15 Jul 2018, 21:47
                $date = $cmspostsresolver->getPostDate($post);
                $format = $fieldArgs['format'];
                if (!$format) {
                    $format = ($cmsengineapi->getDate('Y', $date) == date('Y')) ? 'j M, H:i' : 'j M Y, H:i';
                }
                return $cmsengineapi->getDate($format, $date);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
