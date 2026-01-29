<?php
namespace FzyCommon\Entity\Base;

use FzyCommon\Entity\BaseInterface;
use Psr\Container\ContainerInterface;

interface ServiceAwareEntityInterface extends BaseInterface
{
    /**
     * Set container
     * @param ContainerInterface $container
     * @return $this
     */
    public function setContainer(ContainerInterface $container);

    /**
     * Get container
     * @return ContainerInterface|null
     */
    public function getContainer();
}
