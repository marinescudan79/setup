<?php
/**
 * @Author: Dan Marinescu
 * @Date:   2015-02-17 13:01:19
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-03-21 20:44:17
 */

namespace Db\Service\Invokable;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TableGateway implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
    protected $cache;

    public function init($tableName)
    {
        $cacheKey = $tableName;

        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }
        $this->cache[$cacheKey] = $this->getServiceLocator()->get('getAbstractTable')->init($tableName);


        // \Zend\Debug\Debug::dump($this->cache);
        return $this->cache[$cacheKey];
    }

    /* (non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

    }

    /* (non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
