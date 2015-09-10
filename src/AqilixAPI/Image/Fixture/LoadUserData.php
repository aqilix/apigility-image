<?php
namespace AqilixAPI\Image\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
// use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use AqilixAPI\Image\Entity\User;
use Zend\Crypt\Password\Bcrypt;

// class LoadUserData implements FixtureInterface
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 0;
    }
    
    public function load(ObjectManager $manager)
    {
        $bcrypt   = new Bcrypt();
        $password = $bcrypt->create('2015');
        
        $userData = array(
            array(
                'username' => 'aqilix',
                'email'    => 'aqilix@example.com',
                'profile'  => 'Aqilix',
                'password' => $password,
                'country'  => 'ID',
                'phone_number' => ''
            ),
            array(
                'username' => 'dolly',
                'email'    => 'dolly@example.com',
                'profile'  => 'Dolly',
                'password' => $password,
                'country'  => 'ID',
                'phone_number' => ''
            ),
            array(
                'username' => 'aswin',
                'email'    => 'aswin@example.com',
                'profile'  => 'Aswin',
                'password' => $password,
                'country'  => 'ID',
                'phone_number' => ''
            )
        );
        
        foreach ($userData as $key => $data) {
            $user[$key] = new User();
            $user[$key]->setUsername($data['username']);
            $user[$key]->setEmail($data['email']);
            $user[$key]->setProfile($data['profile']);
            $user[$key]->setPassword($data['password']);
            $user[$key]->setCountry($data['country']);
            $user[$key]->setPhoneNumber($data['phone_number']);
            $manager->persist($user[$key]);
        }
        
        $manager->flush();
        foreach ($userData as $key => $data) {
            $this->addReference('user' . $key, $user[$key]);
        }
    }
}
