<?php
namespace FzyCommon\Entity\Base;

use FzyCommon\Entity\Base;
use Psr\Container\ContainerInterface;

class ServiceAwareEntity extends Base implements ServiceAwareEntityInterface
{
    /**
     * @var ContainerInterface|null
     */
    private $container;

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
     * @return ContainerInterface|null
     */
    public function getContainer()
    {
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
     * @return ContainerInterface|null
     * @deprecated Use getContainer() instead
     */
    public function getServiceLocator()
    {
        return $this->getContainer();
    }
}
