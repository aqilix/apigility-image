<?php

namespace AqilixAPI\Image\Entity;

/**
 * Interface User Entity
 *
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 */
interface UserInterface
{
    public function getId();
    
    public function getUsername();
    
    public function setUsername($username);
    
    public function getPassword();
    
    public function setPassword($password);
}
