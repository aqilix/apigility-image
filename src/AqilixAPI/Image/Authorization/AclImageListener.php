<?php
namespace AqilixAPI\Image\Authorization;

use ZF\MvcAuth\MvcAuthEvent;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;

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
        try {
            $requestedImage = $this->getServiceLocator()->get('image.requested.image');
        } catch (ServiceNotCreatedException $e) {
            // service not created caused by service return null (image not found in database)
            return $mvcAuthEvent->getMvcEvent()
                    ->getResponse()
                    ->setStatusCode(404)
                    ->send();
        }
        
        $authenticatedUser = $this->getServiceLocator()->get('image.authenticated.user');
        // check if requested image owned by authenticated user
        if ($requestedImage->getId() !== null &&
            $requestedImage->getUser()->getId() != $authenticatedUser->getId()) {
            $mvcAuthEvent->setIsAuthorized(false);
        }
    }
}
