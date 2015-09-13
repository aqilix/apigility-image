<?php

namespace AqilixAPI\Image\Stdlib\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Class AssetManagerResolverStrategy
 *
 * @package AqilixAPI\Image\Stdlib\Hydrator\Strategy
 */
class AssetManagerResolverStrategy implements ServiceLocatorAwareInterface, StrategyInterface
{
    use ServiceLocatorAwareTrait;
    
    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param  mixed $value The original value.
     * @param  object $object (optional) The original object for context.
     * @return mixed Returns the value that should be extracted.
     * @throws \RuntimeException If object os not a User
     * 
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function extract($value, $object = null)
    {
        $config = $this->getServiceLocator()->get('Config');
        if (is_string(($value))) {
            return str_replace($config['images']['asset_manager_resolver_path'], '', $value);
        }
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param  mixed $value The original value.
     * @param  array $data (optional) The original data for context.
     * @return mixed Returns the value that should be hydrated.
     * @throws \InvalidArgumentException
     * 
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function hydrate($value, array $data = null)
    {
        return $value;
    }
}
