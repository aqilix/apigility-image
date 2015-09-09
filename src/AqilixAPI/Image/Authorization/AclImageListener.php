<?php
namespace AqilixAPI\Image\Authorization;

use ZF\MvcAuth\MvcAuthEvent;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class AclImageListener implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    
    /**
     * Attempt to authorize the discovered identity based on the ACLs present
     *
     * @param MvcAuthEvent $mvcAuthEvent
     * @void
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
