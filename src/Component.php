<?php
namespace PoP\Posts;

use PoP\Root\Component\AbstractComponent;
use PoP\Posts\Config\ServiceConfiguration;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    // const VERSION = '0.1.0';

    /**
     * Initialize services
     */
    public static function init()
    {
        parent::init();
        ServiceConfiguration::init();
    }
}
