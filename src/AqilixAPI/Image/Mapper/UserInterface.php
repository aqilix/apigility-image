<?php

namespace AqilixAPI\Image\Mapper;

use AqilixAPI\Image\Entity\UserInterface as UserEntityInterface;

/**
 * Interface Image Mapper
 *
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 */
interface UserInterface
{
    public function create(UserEntityInterface $entity);
    
    public function fetchOne($id);
    
    public function fetchAll(array $params);
    
    public function update(UserEntityInterface $entity);
    
    public function delete(UserEntityInterface $entity);
}
