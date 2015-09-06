<?php
/**
 * Image Module
 *
 * @link
 * @copyright Copyright (c) 2015
 */
namespace AqilixAPI\Image;

use ZF\Apigility\Provider\ApigilityProviderInterface;
use Zend\Uri\UriFactory;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;
use Zend\Permissions\Acl\Acl;
use ZF\MvcAuth\MvcAuthEvent;

/**
 * Module Class for image
 *
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 */
class Module implements ApigilityProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        UriFactory::registerScheme('chrome-extension', 'Zend\Uri\Uri'); // add chrome-extension for API Client
        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager   = $e->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        // attach image shared event listener
        $sharedEventManager->attachAggregate($serviceManager->get('AqilixAPI\\Image\\SharedEventListener'));
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        // set role based on oAuth client id
        $eventManager->attach(
            MvcAuthEvent::EVENT_AUTHENTICATION_POST,
            function ($mvcAuthEvent) {
                $authService  = $mvcAuthEvent->getAuthorizationService();
                $identity     = $mvcAuthEvent->getIdentity();
                $authIdentity = $identity->getAuthenticationIdentity();
                if (!$identity instanceof \ZF\MvcAuth\Identity\GuestIdentity) {
                    $identity->setName($authIdentity['client_id']);
                }
            },
            100
        );
        // define ACL
        $eventManager->attach(
            MvcAuthEvent::EVENT_AUTHORIZATION,
            function ($mvcAuthEvent) use ($serviceManager) {
                $config = $serviceManager->get('Config')['authorization'];
                $authService = $mvcAuthEvent->getAuthorizationService();
                // add roles to ACL
                foreach ($config['roles'] as $role) {
                    if (!is_array($role)) {
                        $authService->addRole($role);
                    } else {
                        $authService->addRole($role[0], $role[1]);
                    }
                }
                // add rules
                foreach ($config['rules'] as $type => $rules) {
                    foreach ($rules as $rule) {
                        $authService->setRule(Acl::OP_ADD, $type, $rule['role'], $rule['resource'], $rule['privilege']);
                    }
                }
            },
            100
        );
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/../../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'ZF\Apigility\Autoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }
}
