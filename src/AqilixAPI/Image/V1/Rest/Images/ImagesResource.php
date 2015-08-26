<?php
namespace AqilixAPI\Image\V1\Rest\Images;

use ZF\ApiProblem\ApiProblem;
use AqilixAPI\Image\V1\Rest\AbstractResourceListener;

class ImagesResource extends AbstractResourceListener
{
    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
        return $this->getMapper()->fetchAll($params->toArray());
    }
    
    /**
     * Get Mapper
     *
     * @return AqilixAPI\\Image\Mapper\ImageMapperInterface
     */
    protected function getMapper()
    {
        return $this->getServiceLocator()->get('AqilixAPI\\Image\\Mapper\\Image');
    }
}
