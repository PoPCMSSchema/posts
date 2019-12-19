<?php
namespace PoP\Posts\FieldResolvers;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Posts\TypeResolvers\PostTypeResolver;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\Posts\FieldInterfaces\PublishableArticleFieldInterfaceResolver;

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
            PublishableArticleFieldInterfaceResolver::class,
        ];
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
