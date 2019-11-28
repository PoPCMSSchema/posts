<?php
namespace PoP\Posts\FieldValueResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;

class ExperimentalBranchFieldValueResolver extends PostFieldValueResolver
{
    public function resolveCanProcess(FieldResolverInterface $fieldResolver, string $fieldName, array $fieldArgs = []): bool
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

    public function getSchemaFieldArgs(FieldResolverInterface $fieldResolver, string $fieldName): array
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'excerpt':
                $ret = parent::getSchemaFieldArgs($fieldResolver, $fieldName);
                $ret[] = [
                    'name' => 'branch',
                    'type' => SchemaDefinition::TYPE_STRING,
                    'description' => $translationAPI->__('The branch name, set to value \'experimental\', enabling to use this fieldValueResolver', 'pop-posts'),
                ];
                $ret[] = [
                    'name' => 'length',
                    'type' => SchemaDefinition::TYPE_INT,
                    'description' => $translationAPI->__('Maximum length for the except, in number of characters', 'pop-posts'),
                ];
                $ret[] = [
                    'name' => 'more',
                    'type' => SchemaDefinition::TYPE_STRING,
                    'description' => $translationAPI->__('String to append at the end of the excerpt (if it is shortened by the \'length\' parameter)', 'pop-posts'),
                ];
                return $ret;
        }

        return parent::getSchemaFieldArgs($fieldResolver, $fieldName);
    }

    public function resolveValue(FieldResolverInterface $fieldResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        switch ($fieldName) {
            case 'excerpt':
                // Obtain the required parameter values (or default to some basic values)
                $length = $fieldArgs['length'] ?? 100;
                $more = $fieldArgs['more'] ?? '';
                $excerpt = parent::resolveValue($fieldResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
                return (strlen($excerpt) > $length) ? mb_substr($excerpt, 0, $length) . $more : $excerpt;
        }

        return parent::resolveValue($fieldResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
