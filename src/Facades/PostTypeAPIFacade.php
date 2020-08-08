<?php

declare(strict_types=1);

namespace PoPSchema\Posts\Facades;

use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PostTypeAPIFacade
{
    public static function getInstance(): PostTypeAPIInterface
    {
        return ContainerBuilderFactory::getInstance()->get('post_type_api');
    }
}
