<?php
namespace FzyCommon\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Laminas\ServiceManager\AbstractPluginManager;

/**
 * Abstract Factory for automatically creating FzyCommon view helpers
 * This factory provides automatic dependency injection for all view helpers
 * that extend FzyCommon\View\Helper\Base
 */
class ViewHelperAbstractFactory implements AbstractFactoryInterface
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
        // Check if the class exists and extends FzyCommon\View\Helper\Base
        return class_exists($requestedName) &&
               is_subclass_of($requestedName, \FzyCommon\View\Helper\Base::class);
    }

    /**
     * Create and return the view helper instance
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // The $container here is the ViewHelperManager (a plugin manager)
        // We need to get the main ServiceManager for the view helper to access services
        $serviceManager = $container;
        if ($container instanceof AbstractPluginManager) {
            // Get the parent service locator (main ServiceManager)
            $serviceManager = $container->getServiceLocator();
        }

        // Instantiate the view helper with the main ServiceManager injected
        return new $requestedName($serviceManager);
    }
}
