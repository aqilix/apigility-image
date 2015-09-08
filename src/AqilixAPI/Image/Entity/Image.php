<?php

namespace AqilixAPI\Image\Entity;

use AqilixAPI\Image\Entity\ImageInterface as ImageEntityInterface;
use AqilixAPI\Image\Entity\UserInterface as UserEntityInterface;

/**
 * Image Entity
 *
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 */
class Image implements ImageEntityInterface
{
    /**
     * @var Int
     */
    protected $id;
    
    /**
     * @var UserEntityInterface
     */
    protected $user;
    
    /**
     * @var String
     */
    protected $path;
    
    /**
     * @var String
     */
    protected $thumbPath;
    
    /**
     * @var String
     */
    protected $description;
    
    /**
     * @var \DateTime
     */
    protected $ctime;
    
    /**
     *
     * @var \DateTime
     */
    protected $utime;
    
    /**
     * Get Image ID
     *
     * @return $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Image ID
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
    /**
     * Set user
     *
     * @param $user
     */
    public function setUser(UserEntityInterface $user = null)
    {
        $this->user = $user;
    }
    
    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get Image Path
     *
     * @return $path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set Image Path
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get Image Thumb Path
     *
     * @return $thumbPath
     */
    public function getThumbPath()
    {
        return $this->thumbPath;
    }
    
    /**
     * Set Image Thumb Path
     *
     * @param string $thumbPath
     */
    public function setThumbPath($thumbPath)
    {
        $this->thumbPath = $thumbPath;
    }
    
    /**
     * Get Image Description
     *
     * @return $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set Image Description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get Created Time
     *
     * @return $ctime
     */
    public function getCtime()
    {
        return $this->ctime;
    }

    /**
     * Set Created Time
     *
     * @param \DateTime $ctime
     */
    public function setCtime(\DateTime $ctime)
    {
        $this->ctime = $ctime;
    }

    /**
     * Get Updated Time
     *
     * @return $utime
     */
    public function getUtime()
    {
        return $this->utime;
    }

    /**
     * Set Updated Time
     *
     * @param \DateTime $utime
     */
    public function setUtime(\DateTime $utime)
    {
        $this->utime = $utime;
    }
}
