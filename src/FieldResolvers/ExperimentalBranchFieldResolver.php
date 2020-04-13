<?php
namespace PoP\Posts\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Posts\FieldResolvers\PostContentFieldResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

class ExperimentalBranchFieldResolver extends PostContentFieldResolver
{
    public function resolveCanProcess(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): bool
    {
        // Must specify fieldArg 'branch' => 'experimental'
        return isset($fieldArgs['branch']) && $fieldArgs['branch'] == 'experimental';
    }

    public static function getFieldNamesToResolve(): array
    {
        return [
            'excerpt',
        ];
    }

    public static function getImplementedInterfaceClasses(): array
    {
        return [];
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        $translationAPI = TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'excerpt':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'branch',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The branch name, set to value \'experimental\', enabling to use this fieldResolver', 'pop-posts'),
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'length',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_INT,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Maximum length for the except, in number of characters', 'pop-posts'),
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'more',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('String to append at the end of the excerpt (if it is shortened by the \'length\' parameter)', 'pop-posts'),
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
    }

    public function resolveValue(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        switch ($fieldName) {
            case 'excerpt':
                // Obtain the required parameter values (or default to some basic values)
                $length = $fieldArgs['length'] ?? 100;
                $more = $fieldArgs['more'] ?? '';
                $excerpt = parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
                return (strlen($excerpt) > $length) ? mb_substr($excerpt, 0, $length) . $more : $excerpt;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
