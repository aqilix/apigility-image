<?php
/**
 * Image Module
 *
 * @link
 * @copyright Copyright (c) 2015
 */

namespace AqilixAPI\Image\Service;

use Zend\EventManager\EventInterface;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use AqilixAPI\Image\Event as ImageEvent;

/**
 * Log Event Listener

 * @author  Dolly Aswin <dolly.aswin@gmail.com>
 *
 * @SuppressWarnings(PHPMD)
 */
class SharedEventListener implements SharedListenerAggregateInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    
    /**
     * List of listeners
     *
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = [];
    
    /**
     * Attach listeners to an event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function attachShared(SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('*', ImageEvent::POST_SUCCESS, [$this, 'postSuccess'], 1);
        $this->listeners[] = $events->attach('*', ImageEvent::POST_UPLOAD, [$this, 'backupImage'], 1000);
        $this->listeners[] = $events->attach('*', ImageEvent::POST_UPLOAD, [$this, 'resizeImage'], 500);
        $this->listeners[] = $events->attach('*', ImageEvent::POST_UPLOAD, [$this, 'thumbnailImage'], 100);
        $this->listeners[] = $events->attach('*', ImageEvent::PUT_SUCCESS, [$this, 'putSuccess'], 1);
        $this->listeners[] = $events->attach('*', ImageEvent::DEL_SUCCESS, [$this, 'delSuccess'], 1);
        $this->listeners[] = $events->attach('*', ImageEvent::DEL_FAILED, [$this, 'delFailed'], 1);
    }
    
    /**
     * Detach listeners from an event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function detachShared(SharedEventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detachShared($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
    
    /**
     * Listen to the "api.post.success" event
     *
     * @param EventInterface $event
     */
    public function postSuccess(EventInterface $event)
    {
    }

    /**
     * Listen to the "api.put.success" event
     *
     * @param EventInterface $event
     */
    public function putSuccess(EventInterface $event)
    {
    }

    /**
     * Listen to the "api.del.success" event
     *
     * @param EventInterface $event
     */
    public function delSuccess(EventInterface $event)
    {
        /**
         * Delete files
         */
        $params = $event->getParams();
        unlink($params['path']);
        unlink($params['thumbPath']);
    }
    
    /**
     * Listen to the "api.del.success" event
     *
     * @param EventInterface $event
     */
    public function delFailed(EventInterface $event)
    {
    }
    
    /**
     * Backup Image
     *
     * @param EventInterface $event
     */
    public function backupImage(EventInterface $event)
    {
        $params = $event->getParams();
        $config = $this->getServiceLocator()->get('Config');
        $backupFilePath = $config['images']['ori_path'] . DIRECTORY_SEPARATOR . basename($params->getPath());
        copy($params->getPath(), $backupFilePath);
    }
    
    /**
     * Resize Image
     *
     * @param EventInterface $event
     */
    public function resizeImage(EventInterface $event)
    {
        Resize::save($event->getParams()->getPath(), $event->getParams()->getPath());
    }
    
    /**
     * Thumbnail Image
     *
     * @param EventInterface $event
     */
    public function thumbnailImage(EventInterface $event)
    {
        $params = $event->getParams();
        $config = $this->getServiceLocator()->get('Config');
        $thumbFilePath = $config['images']['thumb_path'] . DIRECTORY_SEPARATOR . basename($params->getPath());
        Resize::save($params->getPath(), $thumbFilePath, 200, 200);
        $params->setThumbPath($thumbFilePath);
    }
}
