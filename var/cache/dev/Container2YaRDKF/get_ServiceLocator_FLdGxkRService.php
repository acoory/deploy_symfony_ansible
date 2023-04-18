<?php

namespace Container2YaRDKF;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_FLdGxkRService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.fLdGxkR' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.fLdGxkR'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'event_dispatcher' => ['services', 'event_dispatcher', 'getEventDispatcherService', false],
            'security.event_dispatcher.api' => ['privates', 'security.event_dispatcher.api', 'getSecurity_EventDispatcher_ApiService', true],
            'security.event_dispatcher.login' => ['privates', 'security.event_dispatcher.login', 'getSecurity_EventDispatcher_LoginService', true],
        ], [
            'event_dispatcher' => '?',
            'security.event_dispatcher.api' => 'Symfony\\Component\\EventDispatcher\\EventDispatcher',
            'security.event_dispatcher.login' => 'Symfony\\Component\\EventDispatcher\\EventDispatcher',
        ]);
    }
}
