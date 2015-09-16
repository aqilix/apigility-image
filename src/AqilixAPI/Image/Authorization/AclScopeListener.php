<?php
namespace AqilixAPI\Image\Authorization;

use ZF\MvcAuth\MvcAuthEvent;
use Zend\Permissions\Acl\Acl;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class AclScopeListener implements ServiceLocatorAwareInterface
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
        $imageService = $this->getServiceLocator()->get('AqilixAPI\\Image\\Service\\Image');
        $authService  = $mvcAuthEvent->getAuthorizationService();
        $config = $this->getServiceLocator()->get('Config')['authorization'];
        $imageService->setUser($this->getServiceLocator()->get('image.authenticated.user'));
        $identity = $mvcAuthEvent->getIdentity();
        if ($identity instanceof \ZF\MvcAuth\Identity\GuestIdentity) {
            return;
        }
        
        // resource:method
        $requestedResource = $mvcAuthEvent->getResource()
                           . ':' . $mvcAuthEvent->getMvcEvent()->getRequest()->getMethod();
        foreach ($config['scopes'] as $scope => $scopeConfig) {
            $resource = $scopeConfig['resource'] . ':' . $scopeConfig['method'];
            // if authorization resource equals to requested resource
            if ($resource == $requestedResource) {
                // check scope in identity
                if (!in_array($scope, explode(' ', $identity->getAuthenticationIdentity()['scope']))) {
                    return $mvcAuthEvent->getMvcEvent()->getResponse()->setStatusCode(401);
                }
            }
        }
    }
}
