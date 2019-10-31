<?php
namespace PoP\Posts\FieldValueResolvers;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldValueResolvers\AbstractDBDataFieldValueResolver;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Posts\FieldResolvers\PostFieldResolver;

class PostFieldValueResolver extends AbstractDBDataFieldValueResolver
{
    public static function getClassesToAttachTo(): array
    {
        return array(PostFieldResolver::class);
    }

    public static function getFieldNamesToResolve(): array
    {
        return [
            'post-type',
            'published',
            'not-published',
            'title',
            'content',
            'url',
            'excerpt',
            'status',
            'is-draft',
            'is-status',
            'date',
            'datetime',
        ];
    }

    public function getSchemaFieldType(FieldResolverInterface $fieldResolver, string $fieldName): ?string
    {
        $types = [
            'post-type' => SchemaDefinition::TYPE_STRING,
            'published' => SchemaDefinition::TYPE_BOOL,
            'not-published' => SchemaDefinition::TYPE_BOOL,
            'title' => SchemaDefinition::TYPE_STRING,
            'content' => SchemaDefinition::TYPE_STRING,
            'url' => SchemaDefinition::TYPE_URL,
            'excerpt' => SchemaDefinition::TYPE_STRING,
            'status' => SchemaDefinition::TYPE_ENUM,
            'is-draft' => SchemaDefinition::TYPE_BOOL,
            'is-status' => SchemaDefinition::TYPE_BOOL,
            'date' => SchemaDefinition::TYPE_DATE,
            'datetime' => SchemaDefinition::TYPE_DATE,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($fieldResolver, $fieldName);
    }

    public function getSchemaFieldDescription(FieldResolverInterface $fieldResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'post-type' => $translationAPI->__('Post type', 'pop-posts'),
            'published' => $translationAPI->__('Has the post been published?', 'pop-posts'),
            'not-published' => $translationAPI->__('Has the post not been published?', 'pop-posts'),
            'title' => $translationAPI->__('Post title', 'pop-posts'),
            'content' => $translationAPI->__('Post content', 'pop-posts'),
            'url' => $translationAPI->__('Post URL', 'pop-posts'),
            'excerpt' => $translationAPI->__('Post excerpt', 'pop-posts'),
            'status' => $translationAPI->__('Post status', 'pop-posts'),
            'is-draft' => $translationAPI->__('Is the post in \'draft\' status?', 'pop-posts'),
            'is-status' => $translationAPI->__('Is the post in the given status?', 'pop-posts'),
            'date' => $translationAPI->__('Post published date', 'pop-posts'),
            'datetime' => $translationAPI->__('Post published date and time', 'pop-posts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($fieldResolver, $fieldName);
    }

    public function getSchemaFieldArgs(FieldResolverInterface $fieldResolver, string $fieldName): array
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($fieldName) {
            case 'date':
                return [
                    [
                        SchemaDefinition::ARGNAME_NAME => 'format',
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                        SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                            $translationAPI->__('Date format, as defined in %s. By default it is \'%s\'', 'pop-posts'),
                            'https://www.php.net/manual/en/function.date.php',
                            $cmsengineapi->getOption(NameResolverFacade::getInstance()->getName('popcms:option:dateFormat'))
                        ),
                    ],
                ];
            case 'datetime':
                return [
                    [
                        SchemaDefinition::ARGNAME_NAME => 'format',
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                        SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                            $translationAPI->__('Date and time format, as defined in %s. By default it is \'j M, H:i\' (for current year date) or \'j M Y, H:i\' (otherwise)', 'pop-posts'),
                            'https://www.php.net/manual/en/function.date.php'
                        ),
                    ],
                ];
            case 'is-status':
                return [
                    [
                        SchemaDefinition::ARGNAME_NAME => 'status',
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ENUM,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The status to check if the post has', 'pop-posts'),
                        SchemaDefinition::ARGNAME_ENUMVALUES => $this->getPostStatuses(),
                        SchemaDefinition::ARGNAME_MANDATORY => true,
                    ],
                ];
        }

        return parent::getSchemaFieldArgs($fieldResolver, $fieldName);
    }

    public function getSchemaFieldDeprecationDescription(FieldResolverInterface $fieldResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $placeholder_status = $translationAPI->__('Use \'is-status(status:%s)\' instead of \'%s\'', 'pop-posts');
        $placeholder_not = $translationAPI->__('Use \'not(fieldname:%s)\' instead of \'%s\'', 'pop-posts');
        $descriptions = [
            'is-draft' => sprintf(
                $placeholder_status,
                \POP_POSTSTATUS_DRAFT,
                $fieldName
            ),
            'published' => sprintf(
                $placeholder_status,
                \POP_POSTSTATUS_PUBLISHED,
                $fieldName
            ),
            'not-published' => sprintf(
                $placeholder_not,
                'published',
                $fieldName
            ),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDeprecationDescription($fieldResolver, $fieldName, $fieldArgs);
    }

    protected function addFieldDocumentation(array &$documentation, string $fieldName)
    {
        switch ($fieldName) {
            case 'status':
                $documentation[SchemaDefinition::ARGNAME_ENUMVALUES] = $this->getPostStatuses();
                break;
        }
    }

    protected function getPostStatuses() {
        return [
            \POP_POSTSTATUS_PUBLISHED,
            \POP_POSTSTATUS_PENDING,
            \POP_POSTSTATUS_DRAFT,
            \POP_POSTSTATUS_TRASH,
        ];
    }

    public function resolveSchemaValidationErrorDescription(FieldResolverInterface $fieldResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        if ($error = parent::resolveSchemaValidationErrorDescription($fieldResolver, $fieldName, $fieldArgs)) {
            return $error;
        }

        $translationAPI = TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'is-status':
                $status = $fieldArgs['status'];
                if (!in_array($status, $this->getPostStatuses())) {
                    return sprintf(
                        $translationAPI->__('Argument \'status\' can only have these values: \'%s\'', 'pop-posts'),
                        implode($translationAPI->__('\', \''), $this->getPostStatuses())
                    );
                }
                break;
        }

        return null;
    }

    public function resolveValue(FieldResolverInterface $fieldResolver, $resultItem, string $fieldName, array $fieldArgs = [])
    {
        $cmspostsresolver = \PoP\Posts\ObjectPropertyResolverFactory::getInstance();
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $cmspostsapi = \PoP\Posts\FunctionAPIFactory::getInstance();
        $post = $resultItem;
        switch ($fieldName) {
            case 'post-type':
                return $cmspostsresolver->getPostType($post);

            case 'title':
                // return HooksAPIFacade::getInstance()->applyFilters('popcms:post:title', $cmspostsresolver->getPostTitle($post), $fieldResolver->getId($post));
                return $cmspostsapi->getPostTitle($fieldResolver->getId($post));

            case 'content':
                $value = $cmspostsapi->getPostContent($fieldResolver->getId($post));
                return HooksAPIFacade::getInstance()->applyFilters('pop_content', $value, $fieldResolver->getId($post));

            case 'url':
                return $cmspostsapi->getPermalink($fieldResolver->getId($post));

            case 'excerpt':
                return $cmspostsapi->getExcerpt($fieldResolver->getId($post));

            case 'status':
                return $cmspostsapi->getPostStatus($fieldResolver->getId($post));

            case 'is-draft':
                return \POP_POSTSTATUS_DRAFT == $cmspostsapi->getPostStatus($fieldResolver->getId($post));

            case 'published':
                return \POP_POSTSTATUS_PUBLISHED == $cmspostsapi->getPostStatus($fieldResolver->getId($post));

            case 'not-published':
                return !$fieldResolver->resolveValue($post, 'published');

            case 'is-status':
                return $fieldArgs['status'] == $cmspostsapi->getPostStatus($fieldResolver->getId($post));

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

        return parent::resolveValue($fieldResolver, $resultItem, $fieldName, $fieldArgs);
    }
}
