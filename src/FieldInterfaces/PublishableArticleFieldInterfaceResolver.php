<?php
namespace PoP\Posts\FieldInterfaces;

use PoP\FieldQuery\FieldQueryUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractSchemaFieldInterfaceResolver;

class PublishableArticleFieldInterfaceResolver extends AbstractSchemaFieldInterfaceResolver
{
    public const NAME = 'PublishableArticle';
    public function getInterfaceName(): string
    {
        return self::NAME;
    }

    public static function getFieldNamesToImplement(): array
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

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
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
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
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
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
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
                            $translationAPI->__('Date format, as defined in %s', 'pop-posts'),
                            'https://www.php.net/manual/en/function.date.php'
                        ),
                        SchemaDefinition::ARGNAME_DEFAULT_VALUE => $cmsengineapi->getOption(NameResolverFacade::getInstance()->getName('popcms:option:dateFormat')),
                    ],
                ];
            case 'datetime':
                return [
                    [
                        SchemaDefinition::ARGNAME_NAME => 'format',
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                        SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                            $translationAPI->__('Date and time format, as defined in %s', 'pop-posts'),
                            'https://www.php.net/manual/en/function.date.php'
                        ),
                        SchemaDefinition::ARGNAME_DEFAULT_VALUE => sprintf(
                            $translationAPI->__('\'%s\' (for current year date) or \'%s\' (otherwise)', 'pop-posts'),
                            'j M, H:i',
                            'j M Y, H:i'
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

        return parent::getSchemaFieldArgs($typeResolver, $fieldName);
    }

    public function getSchemaFieldDeprecationDescription(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?string
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
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDeprecationDescription($typeResolver, $fieldName, $fieldArgs);
    }

    protected function addSchemaDefinitionForField(array &$schemaDefinition, string $fieldName)
    {
        switch ($fieldName) {
            case 'status':
                $schemaDefinition[SchemaDefinition::ARGNAME_ENUMVALUES] = $this->getPostStatuses();
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
                    if (!in_array($status, $this->getPostStatuses())) {
                        return sprintf(
                            $translationAPI->__('Argument \'status\' can only have these values: \'%s\'', 'pop-posts'),
                            implode($translationAPI->__('\', \''), $this->getPostStatuses())
                        );
                    }
                    break;
            }
        }

        return null;
    }
}
