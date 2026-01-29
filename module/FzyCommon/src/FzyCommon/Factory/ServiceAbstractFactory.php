<?php
namespace FzyCommon\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Abstract Factory for automatically creating FzyCommon services
 * This factory provides automatic dependency injection for all services
 * that extend FzyCommon\Service\Base
 */
class ServiceAbstractFactory implements AbstractFactoryInterface
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
        // Check if the class exists and extends FzyCommon\Service\Base
        return class_exists($requestedName) &&
               is_subclass_of($requestedName, \FzyCommon\Service\Base::class);
    }

    /**
     * Create and return the service instance
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Instantiate the service with the container injected
        return new $requestedName($container);
    }
}