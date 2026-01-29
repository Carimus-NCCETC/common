<?php
namespace FzyCommon\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Laminas\ServiceManager\AbstractPluginManager;

/**
 * Abstract Factory for automatically creating FzyCommon controller plugins
 * This factory provides automatic dependency injection for all controller plugins
 * that extend FzyCommon\Controller\Plugin\Base
 */
class ControllerPluginAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Can this factory create the requested service?
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        // Check if the class exists and extends FzyCommon\Controller\Plugin\Base
        return class_exists($requestedName) &&
               is_subclass_of($requestedName, \FzyCommon\Controller\Plugin\Base::class);
    }

    /**
     * Create and return the controller plugin instance
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // The $container here is the ControllerPluginManager (a plugin manager)
        // We need to get the main ServiceManager for the plugin to access services
        $serviceManager = $container;
        if ($container instanceof AbstractPluginManager) {
            $serviceManager = $container->getServiceLocator();
        }

        // Instantiate the controller plugin with the main ServiceManager injected
        return new $requestedName($serviceManager);
    }
}