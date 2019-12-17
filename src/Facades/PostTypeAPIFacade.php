<?php
namespace PoP\Posts\Facades;

use PoP\Posts\TypeAPIs\PostTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PostTypeAPIFacade
{
    public static function getInstance(): PostTypeAPIInterface
    {
        return ContainerBuilderFactory::getInstance()->get('post_type_api');
    }
}
