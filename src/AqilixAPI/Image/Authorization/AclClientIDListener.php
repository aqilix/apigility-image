<?php
namespace AqilixAPI\Image\Authorization;

use ZF\MvcAuth\MvcAuthEvent;
use Zend\Permissions\Acl\Acl;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class AclClientIDListener implements ServiceLocatorAwareInterface
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
        
    }
}
