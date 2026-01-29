<?php
namespace FzyCommon;

use Doctrine\ORM\Events;
use FzyCommon\Listener\ServiceAwareEntity as ServiceAwareEntityListener;
use Laminas\EventManager\EventInterface;
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;

class Module implements BootstrapListenerInterface {
	public function getAutoloaderConfig()
	{
		return array(
			'Laminas\Loader\ClassMapAutoloader' => array(
				__DIR__ . '/autoload_classmap.php',
			),
			'Laminas\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				),
			),
		);
	}

	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}

	/**
	 * Listen to the bootstrap event
	 *
	 * @param EventInterface $e
	 * @return array
	 */
	public function onBootstrap(EventInterface $e)
	{
		/* @var $sm \Laminas\ServiceManager\ServiceLocatorInterface */
		$sm = $e->getApplication()->getServiceManager();
		/* @var $em \Doctrine\ORM\EntityManager */
		$em = $sm->get('Doctrine\ORM\EntityManager');
		$em->getEventManager()->addEventSubscriber(new ServiceAwareEntityListener($sm));
	}


}