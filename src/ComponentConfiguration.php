<?php

declare(strict_types=1);

namespace PoP\Posts;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static $getPostListDefaultLimit;
    private static $getPostListMaxLimit;

    public static function getPostListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::POST_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getPostListDefaultLimit;
        $defaultValue = 10;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function getPostListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::POST_LIST_MAX_LIMIT;
        $selfProperty = &self::$getPostListMaxLimit;
        $defaultValue = -1; // Unlimited
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }
}
