<?php
namespace AqilixAPI\Image\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use ZF\OAuth2\Doctrine\Entity\Client;
use Zend\Crypt\Password\Bcrypt;

class LoadClientData extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 2;
    }
    
    public function load(ObjectManager $manager)
    {
        $bcrypt = new Bcrypt();
        $clientSecret = $bcrypt->create('123456');
        $grantTypes   = array(
          'mobile' => array('client_credentials', 'password', 'implicit', 'refresh_token'),
          'web' => array('password', 'implicit', 'refresh_token')
        );
        $redirectUri  = '/oauth/receivecode';
        $mobileScope  = array(
                    $this->getReference('scope0'),
                    $this->getReference('scope1'),
                    $this->getReference('scope2'),
                );
        $webScope = array($this->getReference('scope0'), $this->getReference('scope1'), $this->getReference('scope2'));
        
        $clientData = array(
            array(
                'user'   => $this->getReference('user0'),
                'secret' => $clientSecret,
                'client_id'  => 'mobile',
                'grant_type' => $grantTypes['mobile'],
                'scope'  => $mobileScope
            ),
            array(
                'user'   => $this->getReference('user0'),
                'secret' => $clientSecret,
                'client_id'  => 'web',
                'grant_type' => $grantTypes['web'],
                'scope'  => $webScope
            ),
            array(
                'user'   => $this->getReference('user1'),
                'secret' => $clientSecret,
                'client_id'  => 'mobile1',
                'grant_type' => $grantTypes['mobile'],
                'scope'  => $mobileScope
            ),
            array(
                'user'   => $this->getReference('user1'),
                'secret' => $clientSecret,
                'client_id'  => 'web1',
                'grant_type' => $grantTypes['web'],
                'scope'  => $webScope
            ),
            array(
                'user'   => $this->getReference('user2'),
                'secret' => $clientSecret,
                'client_id'  => 'mobile2',
                'grant_type' => $grantTypes['mobile'],
                'scope'  => $mobileScope
            ),
            array(
                'user'   => $this->getReference('user2'),
                'secret' => $clientSecret,
                'client_id'  => 'web2',
                'grant_type' => $grantTypes['web'],
                'scope'  => $webScope
            ),
        );
        
        foreach ($clientData as $key => $data) {
            $client[$key] = new Client();
            $client[$key]->setUser($data['user']);
            $client[$key]->setSecret($data['secret']);
            $client[$key]->setClientId($data['client_id']);
            $client[$key]->setRedirectUri($redirectUri);
            $client[$key]->setGrantType($data['grant_type']);
            foreach ($data['scope'] as $scope) {
                $client[$key]->addScope($scope);
                $scope->addClient($client[$key]);
                $manager->persist($scope);
            }
            
            $manager->persist($client[$key]);
        }
        
        $manager->flush();
        foreach ($clientData as $key => $data) {
            $this->addReference('client' . $key, $client[$key]);
        }
    }
}
