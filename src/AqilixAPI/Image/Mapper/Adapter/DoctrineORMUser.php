<?php

namespace AqilixAPI\Image\Mapper\Adapter;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use AqilixAPI\Image\Mapper\UserInterface as UserMapperInterface;
use AqilixAPI\Image\Entity\UserInterface as UserEntityInterface;

/**
 * User Mapper with Doctrine support
 *
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 */
class DoctrineORMUser implements UserMapperInterface, ServiceLocatorAwareInterface
{
    /**
     * @var Doctrine\ORM\EntityManagerInterface
     */
    protected $em;
    
    /**
     * Create User
     *
     * @param UserEntityInterface $entity
     */
    public function create(UserEntityInterface $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    
        return $entity;
    }
    
    /**
     * Fetch User by Id
     *
     * @param int $id
     */
    public function fetchOne($id)
    {
        return $this->getEntityRepository()->findOneBy(array('id' => $id));
    }

    /**
     * Fetch Users with pagination
     *
     * @param  array $params
     */
    public function fetchAll(array $params)
    {
    }
    
    
    /**
     * Update User
     *
     * @param array $data
     */
    public function update(UserEntityInterface $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        
        return $entity;
    }
    
    /**
     * Delete User
     *
     * @param UserEntityInterface $entity
     */
    public function delete(UserEntityInterface $entity)
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
    
    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->sm = $serviceLocator;
    }
    
    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->sm;
    }
    
    /**
     * Set EntityManager
     *
     * @param EntityManagerInterface $serviceLocator
     */
    public function setEntityManager(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    /**
     * Get EntityManager
     *
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        if ($this->em === null) {
            $this->setEntityManager($this->getServiceLocator()->get('Doctrine\\ORM\\EntityManager'));
        }
        
        return $this->em;
    }
    
    /**
     * Get Entity Repository
     */
    protected function getEntityRepository()
    {
        return $this->getEntityManager()->getRepository('AqilixAPI\\Image\\Entity\\User');
    }
}
