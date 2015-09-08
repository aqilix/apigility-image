<?php

namespace AqilixAPI\Image\Mapper\Adapter;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use AqilixAPI\Image\Mapper\ImageInterface as ImageMapperInterface;
use AqilixAPI\Image\Entity\ImageInterface as ImageEntityInterface;
use Zend\Paginator\Paginator as ZendPaginator;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrinePaginatorAdapter;

/**
 * Image Mapper with Doctrine support
 *
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 */
class DoctrineORMImage implements ImageMapperInterface, ServiceLocatorAwareInterface
{
    /**
     * @var Doctrine\ORM\EntityManagerInterface
     */
    protected $em;
    
    /**
     * @var Zend\Stdlib\Hydrator\HydratorInterface
     */
    protected $hydrator;
    
    /**
     * Create Image
     *
     * @param ImageEntityInterface $entity
     */
    public function create(ImageEntityInterface $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        
        return $entity;
    }

    /**
     * Fetch Image by Id
     *
     * @param int $id
     */
    public function fetchOne($id)
    {
        return $this->getEntityRepository()->findOneBy(array('id' => $id));
    }

    /**
     * Fetch Images with pagination
     *
     * @param  array $params
     * @return ZendPaginator
     */
    public function fetchAll(array $params)
    {
        $qb = $this->getEntityRepository()->createQueryBuilder('image');
        $query = $qb->getQuery();
        $query->useQueryCache(true);
        $query->useResultCache(true, 600, 'image-list');
        return $query;
    }
    
    /**
     * Get Paginator Adapter for list
     *
     * @param  unknown $query
     * @param  boolean $fetchJoinCollection
     * @return DoctrineORMModule\Paginator\Adapter\DoctrinePaginator
     */
    public function buildListPaginatorAdapter(array $params)
    {
        $query   = $this->fetchAll($params);
        $doctrinePaginator = new DoctrinePaginator($query, true);
        $adapter = new DoctrinePaginatorAdapter($doctrinePaginator);
    
        return $adapter;
    }
    
    
    /**
     * Update Image
     *
     * @param array $data
     */
    public function update(ImageEntityInterface $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        
        return $entity;
    }
    
    /**
     * Delete Image
     *
     * @param ImageEntityInterface $entity
     */
    public function delete(ImageEntityInterface $entity)
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
     * Set Hydrator
     *
     * @param HydratorInterface $hydrator
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }
    
    /**
     * Get Hydrator
     *
     * @return HydratorInterface
     */
    public function getHydrator()
    {
        if ($this->hydrator === null) {
            $hydratorManager = $this->getServiceLocator()->get('HydratorManager');
            $this->setHydrator($hydratorManager->get('AqilixAPI\\Image\\Entity\\Hydrator'));
        }
    
        return $this->hydrator;
    }
    
    /**
     * Get Entity Repository
     */
    protected function getEntityRepository()
    {
        return $this->getEntityManager()->getRepository('AqilixAPI\Image\Entity\Image');
    }
}
