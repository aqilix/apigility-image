<?php
namespace AqilixAPI\Image\Service;

use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Paginator\Adapter\AdapterInterface as PaginatorAdapter;
use Zend\Paginator\Paginator as ZendPaginator;
use Zend\InputFilter\InputFilter;
use Zend\Filter;
use AqilixAPI\Image\Entity\User;
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
     * @var array
     */
    protected $content;
    
    /**
     * @var \AqilixAPI\Image\Entity\User
     */
    protected $user;

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
     * Set Content
     *
     * @param array $content
     */
    public function setContent(array $content)
    {
        $this->content = $content;
    }
    
    /**
     * Get Content
     */
    public function getContent()
    {
        if ($this->content !== null) {
            return $this->content;
        }
        
        $config = $this->getServiceLocator()->get('Config');
        $data   = array();
        $inputFilter = $this->getInputFilter();
        if ($inputFilter instanceof InputFilter) {
            // add filter for fileinput
            $fileInput = $inputFilter->get('image');
            $fileInput->getFilterChain()
                ->attach(new Filter\File\RenameUpload(array(
                    'target' => $config['images']['target'],
                    'randomize' => true,
                    'use_upload_extension' => true
                )));
            if ($inputFilter->getValue('image') !== null) {
                $data['path'] = $inputFilter->getValue('image')['tmp_name'];
            }
            
            if ($this->getIdentifier() === null) {
                $data['ctime'] = new \DateTime();
            } else {
                $data['utime'] = new \DateTime();
            }
            
            $data['description'] = $inputFilter->getValue('description');
            $data['user'] = $this->getUser();
        }
        
        $this->content = $data;
        return $this->content;
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
        if ($this->entity !== null) {
            return $this->entity;
        } elseif ($this->getIdentifier() === null) {
            $data = $this->getContent();
            $this->entity = $this->getMapper()->getHydrator()->hydrate($data, new ImageEntity());
        } else {
            $data   = $this->getContent();
            $entity = $this->getServiceLocator()->get('image.requested.image');
            $this->entity = $this->getMapper()->getHydrator()->hydrate($data, $entity);
        }
        
        return $this->entity;
    }
    
    /**
     * Get User Entity
     * 
     * @return User
     */
    public function getUser()
    {
        if ($this->user === null) {
            $this->setUser($this->getServiceLocator()->get('image.authenticated.user'));
        }
        
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
    
    /**
     * Get Collection
     * 
     * @param array $params
     * @return \Zend\Paginator\Paginator
     */
    public function getCollection(array $params)
    {
        $params  = array('image.user' => $this->getUser());
        $adapter = $this->getMapper()->buildListPaginatorAdapter($params);
        return self::buildPaginator($adapter);
    }
    
    /**
     * Get Array of Entity
     */
    public function getArrayEntity()
    {
        $mapper   = $this->getServiceLocator()->get('AqilixAPI\\Image\\Mapper\\Image');
        $hydrator = $mapper->getHydrator();
        // remove asset manager aliasing path hydrator
        $hydrator->removeStrategy('path');
        $hydrator->removeStrategy('thumbPath');
        return $hydrator->extract($this->getEntity());
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
