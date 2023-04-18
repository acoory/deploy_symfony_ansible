<?php

namespace ContainerH9gkWUd;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_VIY6kUBService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.VIY6kUB' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.VIY6kUB'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'authUtils' => ['privates', 'security.authentication_utils', 'getSecurity_AuthenticationUtilsService', true],
        ], [
            'authUtils' => '?',
        ]);
    }
}