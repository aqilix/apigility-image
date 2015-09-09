<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace AqilixAPI\Image\Authorization;

// use Zend\Http\Request;
// use Zend\Http\Response;
// use Zend\Mvc\Router\RouteMatch;
use ZF\MvcAuth\MvcAuthEvent;
// use ZF\MvcAuth\Identity\IdentityInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class AclImageListener implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    
    /**
     * Attempt to authorize the discovered identity based on the ACLs present
     *
     * @param MvcAuthEvent $mvcAuthEvent
     * @return bool
     */
    public function __invoke(MvcAuthEvent $mvcAuthEvent)
    {
        $mvcEvent = $mvcAuthEvent->getMvcEvent();
        $requestedImage    = $this->getServiceLocator()->get('image.requested.image');
        $authenticatedUser = $this->getServiceLocator()->get('image.authenticated.user');
        // check if requested image owned by authenticated user
        if ($requestedImage->getId() !== null &&
            $requestedImage->getUser()->getId() != $authenticatedUser->getId()) {
            $mvcAuthEvent->setIsAuthorized(false);
        }
    }
}
