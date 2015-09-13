<?php

namespace AqilixAPI\Image\Stdlib\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;
use AqilixAPI\Image\Entity\User;

/**
 * Class UsernameStrategy
 *
 * @package AqilixAPI\Image\Stdlib\Hydrator\Strategy
 */
class UsernameStrategy implements StrategyInterface
{
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
        if ($value instanceof User) {
            return $value->getUsername();
        }

        return null;
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
