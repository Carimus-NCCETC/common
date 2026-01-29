<?php
namespace FzyCommon\Service;

use FzyCommon\Util\Params;
use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface;

/**
 * Class Base
 * @package FzyCommon\Service
 */
abstract class Base
{
    const MODULE_CONFIG_KEY = 'fzycommon';

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Params
     */
    protected $config;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var LoggerInterface
     */
    protected $logger;

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
     * Get the container
     * @return ContainerInterface
     * @throws \RuntimeException
     */
    protected function getContainer()
    {
        if (!$this->container) {
            throw new \RuntimeException('Container not set. Service must be instantiated via factory.');
        }
        return $this->container;
    }

    /**
     * Backward compatibility alias for getContainer()
     * @return ContainerInterface
     * @deprecated Use getContainer() instead
     */
    protected function getServiceLocator()
    {
        return $this->getContainer();
    }

    /**
     * Get the application config as a Params object
     * @return Params
     */
    public function getConfig()
    {
        if (!isset($this->config)) {
            $this->config = $this->getContainer()->get('FzyCommon\Config');
        }

        return $this->config;
    }

    /**
     * Get the module config (application config section in the module key namespace specified by static::MODULE_CONFIG_KEY)
     * @return Params
     */
    public function getModuleConfig()
    {
        return $this->getConfig()->getWrapped(static::MODULE_CONFIG_KEY);
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
     * Backward compatibility method for setting service locator
     * @param ContainerInterface $serviceLocator
     * @return $this
     * @deprecated Use setContainer() instead
     */
    public function setServiceLocator($serviceLocator)
    {
        return $this->setContainer($serviceLocator);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function em()
    {
        if (!isset($this->em)) {
            $this->em = $this->getContainer()->get('Doctrine\ORM\EntityManager');
        }

        return $this->em;
    }

    /**
     * @return \FzyCommon\Service\Url
     */
    public function url()
    {
        return $this->getContainer()->get('FzyCommon\Service\Url');
    }

    /**
     * @param $className
     * @param $id
     * @return \FzyCommon\Entity\BaseInterface
     * @throws \RuntimeException
     */
    public function lookup($className, $id)
    {
        $entity = !empty($id) ? $this->em()->find($className, $id) : null;

        $nullClass = $className.'Null';
        if ($className[0] != '\\') {
            $nullClass = '\\'.$nullClass;
        }
        if ($entity == null) {
            if (!class_exists($nullClass)) {
                throw new \RuntimeException("$nullClass does not exist");
            }
            $entity = new $nullClass();
        }

        return $entity;
    }

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * write to the log if it exists
     * @param string $logMessage
     * @param string $logType
     * @return $this
     */
    protected function log($logMessage, $extra = array(), $logType = 'err') {
        $map = array(
            'emerg' => 'emergency', 'alert' => 'alert', 'crit' => 'critical',
            'err' => 'error', 'warn' => 'warning', 'notice' => 'notice',
            'info' => 'info', 'debug' => 'debug',
        );
        if ($this->getLogger()) {
            $method = isset($map[$logType]) ? $map[$logType] : 'error';
            $this->getLogger()->$method($logMessage, $extra);
        }

        return $this;
    }
}
