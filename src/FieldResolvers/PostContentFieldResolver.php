<?php
namespace PoP\Posts\FieldResolvers;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Posts\TypeResolvers\PostTypeResolver;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Content\FieldInterfaces\ContentFieldInterfaceResolver;
use PoP\Content\FieldInterfaces\LinkableFieldInterfaceResolver;
use PoP\Content\FieldInterfaces\PublishableFieldInterfaceResolver;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\Posts\Facades\PostTypeAPIFacade;

class PostContentFieldResolver extends AbstractDBDataFieldResolver
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

    public function resolveValue(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        $cmspostsresolver = \PoP\Posts\ObjectPropertyResolverFactory::getInstance();
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        $post = $resultItem;
        switch ($fieldName) {
            case 'post-type':
                return $cmspostsresolver->getPostType($post);

            case 'title':
                return $postTypeAPI->getTitle($post);

            case 'content':
                $value = $postTypeAPI->getContent($post);
                return HooksAPIFacade::getInstance()->applyFilters('pop_content', $value, $typeResolver->getId($post));

            case 'url':
                return $postTypeAPI->getPermalink($post);

            case 'excerpt':
                return $postTypeAPI->getExcerpt($post);

            case 'status':
                return $postTypeAPI->getStatus($post);

            case 'is-status':
                return $fieldArgs['status'] == $postTypeAPI->getStatus($post);

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
