<?php
namespace FzyCommon;
use Aws\S3\S3Client;
use FzyCommon\Service\Base;

return array(
	'service_manager' => array(
		'abstract_factories' => array(
			\FzyCommon\Factory\ServiceAbstractFactory::class,
		),
        'aliases' => array(
            'FzyCommon\EntityToForm' => 'FzyCommon\Service\EntityToForm',
            'FzyCommon\Search\Result' => 'FzyCommon\Service\Search\Result',
            'FzyCommon\Url' => 'FzyCommon\Service\Url',
            'FzyCommon\Render' => 'FzyCommon\Service\Render',
        ),
		'factories' => array(
			'FzyCommon\Config' => function($sm) {
				return \FzyCommon\Util\Params::create($sm->get('config'));
			},
			'FzyCommon\ModuleConfig' => function($sm) {
				return $sm->get('FzyCommon\Config')->getWrapped(Base::MODULE_CONFIG_KEY);
			},
			'FzyCommon\Service\Aws\Config' => function($sm) {
				return $sm->get('FzyCommon\ModuleConfig')->getWrapped('aws');
			},
			/**
			 * @return \FzyCommon\Util\Params
			 */
			'FzyCommon\Service\Aws\S3\Config' => function($sm) {
				return $sm->get('FzyCommon\Service\Aws\Config')->getWrapped('s3');
			},
			/**
			 * @return \Aws\S3\S3Client
			 */
			'FzyCommon\Service\Aws\S3' => function($sm) {
				$config = $sm->get('FzyCommon\Service\Aws\S3\Config')->get();
				return new S3Client(array(
					'credentials' => $config['credentials'],
					'region' => $config['region'],
					'version' => $config['version'],
				));
			}
		),
	),
	'controller_plugins' => array(
		'abstract_factories' => array(
			\FzyCommon\Factory\ControllerPluginAbstractFactory::class,
		),
		'aliases' => array(
			'fzySearchResult' => 'FzyCommon\Controller\Plugin\SearchResult',
			'fzyUpdateResult' => 'FzyCommon\Controller\Plugin\UpdateResult',
			'fzyEntityToForm' => 'FzyCommon\Controller\Plugin\EntityToForm',
		),
	),
	'view_helpers' => array(
		'abstract_factories' => array(
			\FzyCommon\Factory\ViewHelperAbstractFactory::class,
		),
		'aliases' => array(
			'fzyEntityToForm' => 'FzyCommon\View\Helper\EntityToForm',
			'FzyEntityToForm' => 'FzyCommon\View\Helper\EntityToForm',
			'fzyNgInit' => 'FzyCommon\View\Helper\NgInit',
			'FzyNgInit' => 'FzyCommon\View\Helper\NgInit',
			'fzyRequest' => 'FzyCommon\View\Helper\Request',
			'FzyRequest' => 'FzyCommon\View\Helper\Request',
		),
		'factories' => array(
			'fzyFlashMessages' => function($sm) {
				$flashmessenger = $sm->getServiceLocator()
				                     ->get('ControllerPluginManager')
				                     ->get('flashmessenger');

				$messages = new \FzyCommon\View\Helper\FlashMessages();
				$messages->setFlashMessenger($flashmessenger);

				return $messages;
			},
		),
	),
	'doctrine' => array(
		'driver' => array(
			__NAMESPACE__ . '_driver' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
			),
			'orm_default' => array(
				'drivers' => array(
					__NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
				)
			)
		),
		'configuration' => array(
			'orm_default' => array(
				'generate_proxies' => false,
			),
		),
	),
	Base::MODULE_CONFIG_KEY => array(
		'debug' => false,
		'production' => true,
	),
);
