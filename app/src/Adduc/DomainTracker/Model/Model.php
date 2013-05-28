<?php

namespace Adduc\DomainTracker\Model;
use Adduc\DomainTracker\Exception;
use Adduc\DomainTracker\Core;
use Doctrine\Common\Inflector\Inflector;

class Model {

    protected
        $config;

    public function __construct(Core\Config $config = null) {
        $this->config = $config ?: new Core\Config();
    }

    public function getModel($model) {
        $class = Inflector::classify($model);
        $class = __NAMESPACE__  . "\\{$class}Model";
        switch(true) {
            case !class_exists($class, true):
            case !is_subclass_of($class, __CLASS__):
                $msg = "{$class} does not exist.";
                throw new Exception\ModelNotFound($msg);
        }

        return new $class($this->config);
    }

}
