<?php

namespace AqilixAPI\Image\Entity;

use ZF\OAuth2\Doctrine\Entity\UserInterface;
use Zend\Stdlib\ArraySerializableInterface;
use Doctrine\Common\Collections\ArrayCollection;

class User implements UserInterface, ArraySerializableInterface
{
    protected $id;
    protected $client;
    protected $images;
    protected $accessToken;
    protected $authorizationCode;
    protected $refreshToken;

    // OpenID fields
    protected $username;
    protected $password;
    protected $profile;
    protected $email;
    protected $country;
    protected $phoneNumber;

    public function exchangeArray(array $data)
    {
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'username':
                    $this->setUsername($value);
                    break;
                case 'password':
                    $this->setPassword($value);
                    break;
                case 'profile':
                    $this->setProfile($value);
                    break;
                case 'email':
                    $this->setEmail($value);
                    break;
                case 'country':
                    $this->setAddress($value);
                    break;
                case 'phone_number':
                case 'phoneNumber':
                    $this->setPhone($value);
                    break;
                default:
                    break;
            }
        }

        return $this;
    }

    public function getArrayCopy()
    {
        return array(
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            'profile' => $this->getProfile(),
            'email' => $this->getEmail(),
            'country' => $this->getCountry(),
            'phone_number' => $this->getPhoneNumber(), // underscore formatting for openid
            'phoneNumber' => $this->getPhoneNumber(),
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($value)
    {
        $this->phoneNumber = $value;

        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($value)
    {
        $this->country = $value;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($value)
    {
        $this->email = $value;

        return $this;
    }

    public function getProfile()
    {
        return $this->profile;
    }

    public function setProfile($value)
    {
        $this->profile = $value;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($value)
    {
        $this->username = $value;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($value)
    {
        $this->password = $value;

        return $this;
    }

    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set images
     * 
     * @param  \Doctrine\Common\Collections\ArrayCollection $images
     * @return \Application\Entity\User
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * Get images
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add image
     * 
     * @param  AqilixAPI\Image\Entity\Image $image
     */
    public function addImage(Image $image)
    {
        $this->images->add($image);
    }

    /**
     * Add images
     * 
     * @param  \Doctrine\Common\Collections\ArrayCollection $images
     */
    public function addImages(ArrayCollection $images)
    {
        foreach ($images as $image) {
            $this->images->add($image);
        }
    }

    /**
     * Remove images
     * 
     * @param  \Doctrine\Common\Collections\ArrayCollection $images
     */
    public function removeImages(ArrayCollection $images)
    {
        foreach ($images as $image) {
            $this->images->removeElement($image);
        }
    }
    
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    public function getRefreshToken()
    {
        return $this->refreshToken;
    }
}
