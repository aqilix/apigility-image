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
        $imageService = $this->getServiceLocator()->get('AqilixAPI\\Image\\Service\\Image');
        return $imageService->getCollection($params->toArray());
    }
}
