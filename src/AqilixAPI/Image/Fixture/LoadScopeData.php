<?php
namespace AqilixAPI\Image\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use ZF\OAuth2\Doctrine\Entity\Scope;

class LoadScopeData extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 1;
    }
    
    public function load(ObjectManager $manager)
    {
        $scopeData = array(
            array(
                'scope' => 'read',
                'is_default' => 1
            ),
            array(
                'scope' => 'like',
                'is_default' => 1
            ),
            array(
                'scope' => 'comment',
                'is_default' => 1
            ),
            array(
                'scope' => 'post',
                'is_default' => 1
            ),
            array(
                'scope' => 'update',
                'is_default' => 1
            ),
            array(
                'scope' => 'delete',
                'is_default' => 1
            ),
        );
        
        foreach ($scopeData as $key => $data) {
            $scope[$key] = new Scope();
            $scope[$key]->setScope($data['scope']);
            $scope[$key]->setIsDefault($data['is_default']);
            $manager->persist($scope[$key]);
        }
        
        $manager->flush();
        foreach ($scopeData as $key => $data) {
            $this->addReference('scope' . $key, $scope[$key]);
        }
    }
}
