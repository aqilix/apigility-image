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
use ZF\MvcAuth\MvcAuthEvent;

/**
 * Module Class for image
 *
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 */
class Module implements ApigilityProviderInterface
{
    public function onBootstrap(MvcEvent $mvcEvent)
    {
        UriFactory::registerScheme('chrome-extension', 'Zend\Uri\Uri'); // add chrome-extension for API Client
        $serviceManager = $mvcEvent->getApplication()->getServiceManager();
        $eventManager   = $mvcEvent->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        // attach image shared event listener
        $sharedEventManager->attachAggregate($serviceManager->get('AqilixAPI\\Image\\SharedEventListener'));
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        // set role based on oAuth client id
        $eventManager->attach(
            MvcAuthEvent::EVENT_AUTHENTICATION_POST,
            function ($mvcAuthEvent) {
                $identity     = $mvcAuthEvent->getIdentity();
                $authIdentity = $identity->getAuthenticationIdentity();
                if (!$identity instanceof \ZF\MvcAuth\Identity\GuestIdentity) {
                    $identity->setName($authIdentity['client_id']);
                }
            },
            100
        );
        // attach ACL for checking Client ID
        $eventManager->attach(
            MvcAuthEvent::EVENT_AUTHORIZATION,
            $serviceManager->get('AqilixAPI\\Image\\Authorization\\AclClientIDListener'),
            100
        );
        // attach ACL for checking image owner
        $eventManager->attach(
            MvcAuthEvent::EVENT_AUTHORIZATION_POST,
            $serviceManager->get('AqilixAPI\\Image\\Authorization\\AclImageListener'),
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
