<?php

namespace ContainerH9gkWUd;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getApiAuthControllerService extends App_KernelDevDebugContainer
{
    /**
     * Gets the public 'App\Controller\ApiAuthController' shared autowired service.
     *
     * @return \App\Controller\ApiAuthController
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/symfony/framework-bundle/Controller/AbstractController.php';
        include_once \dirname(__DIR__, 4).'/src/Controller/ApiAuthController.php';

        $container->services['App\\Controller\\ApiAuthController'] = $instance = new \App\Controller\ApiAuthController();

        $instance->setContainer(($container->privates['.service_locator.CshazM0'] ?? $container->load('get_ServiceLocator_CshazM0Service'))->withContext('App\\Controller\\ApiAuthController', $container));

        return $instance;
    }
}