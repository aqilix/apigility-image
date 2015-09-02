<?php
namespace AqilixAPI\Image\Service;

use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Paginator\Adapter\AdapterInterface as PaginatorAdapter;
use Zend\Paginator\Paginator as ZendPaginator;
use Zend\Filter;
use AqilixAPI\Image\Entity\ImageInterface as ImageEntityInterface;
use AqilixAPI\Image\Entity\Image as ImageEntity;
use AqilixAPI\Image\Mapper\ImageInterface as ImageMapperInterface;

class Image implements ServiceLocatorAwareInterface
{
    /**
     * @var int
     */
    protected $identifier;

    /**
     * @var \AqilixAPI\Image\Entity\ImageEntityInterface
     */
    protected $entity;
    
    /**
     * @var \Zend\InputFilter\InputFilterInterface
     */
    protected $inputFilter;
    
    /**
     * @var \AqilixAPI\Image\Mapper\ImageInterface
     */
    protected $mapper;
    
    use ServiceLocatorAwareTrait;

    /**
     * Set Identifier
     *
     * @param int $entity
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }
    
    /**
     * Get Identifier
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
    
    /**
     * Set Entity
     *
     * @param ImageEntityInterface $entity
     */
    public function setEntity(ImageEntityInterface $entity)
    {
        $this->entity = $entity;
    }
    
    /**
     * Get Entity
     *
     * @return $entity
     */
    public function getEntity()
    {
        $data   = array();
        $config = $this->getServiceLocator()->get('Config');
        $inputFilter = $this->getInputFilter();
        if ($inputFilter instanceof Filter) {
            // add filter for fileinput
            $fileInput   = $inputFilter->get('image');
            $fileInput->getFilterChain()
                ->attach(new Filter\File\RenameUpload(array(
                    'target' => $config['images']['target'],
                    'randomize' => true,
                    'use_upload_extension' => true
            )));
        }
        
        if ($this->entity === null && $this->getIdentifier() === null) {
            // new image entity
            $data = array(
                'description' => $inputFilter->getValue('description'),
                'path'  => $inputFilter->getValue('image')['tmp_name'],
                'ctime' => new \DateTime()
            );
            $this->entity = $this->getMapper()->getHydrator()->hydrate($data, new ImageEntity());
        } else {
            // load entity based on ID
            $this->entity = $this->getMapper()->fetchOne($this->getIdentifier());
            if ($inputFilter !== null) {
                $data = array(
                    'description' => $inputFilter->getValue('description'),
                    'utime' => new \DateTime()
                );
            }
            
            $this->entity = $this->getMapper()->getHydrator()->hydrate($data, $this->entity);
        }
        
        return $this->entity;
    }
    
    /**
     * Get Collection
     * 
     * @param array $params
     * @return \Zend\Paginator\Paginator
     */
    public function getCollection(array $params)
    {
        $adapter = $this->getMapper()->buildListPaginatorAdapter($params);
        return self::buildPaginator($adapter);
    }
    
    /**
     * Get Array of Entity
     */
    public function getArrayEntity()
    {
        $mapper = $this->getServiceLocator()->get('AqilixAPI\\Image\\Mapper\\Image');
        return $mapper->getHydrator()->extract($this->getEntity());
    }
    
    /**
     * Set InputFilter
     *
     * @param InputFilterInterface $inputFilter
     */
    public function setInputFilter(InputFilterInterface $inputFilter = null)
    {
        $this->inputFilter = $inputFilter;
    }
    
    /**
     * Get InputFilter
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        return $this->inputFilter;
    }

    /**
     * Build Paginator
     * 
     * @param  \Zend\Paginator\Adapter\AdapterInterface $paginatorAdapter
     * @return \Zend\Paginator\Paginator
     */
    public static function buildPaginator(PaginatorAdapter $paginatorAdapter)
    {
        return new ZendPaginator($paginatorAdapter);
    }
 
    /**
     * Get Mapper
     * 
     * @return ImageMapperInterface
     */
    public function getMapper()
    {
        if ($this->mapper === null) {
            $this->setMapper($this->getServiceLocator()->get('AqilixAPI\\Image\\Mapper\\Image'));
        }
        
        return $this->mapper;
    }

    /**
     * Set Mapper
     * 
     * @param AqilixAPI\Image\Mapper\ImageInterface $mapper
     */
    public function setMapper(ImageMapperInterface $mapper)
    {
        $this->mapper = $mapper;
    }
}
