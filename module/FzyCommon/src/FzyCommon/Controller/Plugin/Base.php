<?php

namespace FzyCommon\Controller\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Psr\Container\ContainerInterface;

abstract class Base extends AbstractPlugin
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor
     * @param ContainerInterface|null $container
     */
    public function __construct(ContainerInterface $container = null)
    {
        if ($container !== null) {
            $this->container = $container;
        }
    }

    /**
     * Set container
     * @param ContainerInterface $container
     * @return $this
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * Get container
     * @return ContainerInterface
     * @throws \RuntimeException
     */
    protected function getContainer()
    {
        if (!$this->container) {
            throw new \RuntimeException('Container not set. Plugin must be instantiated via factory.');
        }
        return $this->container;
    }

    /**
     * Backward compatibility: Set service locator
     * @param ContainerInterface $serviceLocator
     * @return $this
     * @deprecated Use setContainer() instead
     */
    public function setServiceLocator($serviceLocator)
    {
        return $this->setContainer($serviceLocator);
    }

    /**
     * Backward compatibility: Get service locator
     * @return ContainerInterface
     * @deprecated Use getContainer() instead
     */
    public function getServiceLocator()
    {
        return $this->getContainer();
    }

    /**
     * Get a service from the container
     * @param string $key
     * @return mixed
     */
    protected function getService($key)
    {
        return $this->getContainer()->get($key);
    }
}
