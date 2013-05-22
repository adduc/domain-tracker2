<?php
namespace Adduc\DomainTracker\Controller;
use Doctrine\Common\Inflector\Inflector;
use Adduc\DomainTracker\Exception;
use Adduc\DomainTracker\Core\Config;

class Controller {

    protected
        $config,
        $view,
        $layout,
        $viewVars;

    public function __construct(Config $config = null) {
        $this->config = $config ?: new Config();
    }

    public function run($action, array $params) {
        $action = Inflector::camelize($action) . "Action";
        if(!method_exists($this, $action)) {
            throw new Exception\Ex404("{$action} does not exist.");
        }

        $this->$action();
        $this->render();
    }

    public function render($view = null, $layout = null) {
        is_null($view) && $view = $this->view;
        is_null($layout) && $layout = $this->layout;

    }

    public function getConfig() {
        return $this->config;
    }

    public function setConfig(Config $config) {
        $this->config = $config;
    }

    public function getController($controller) {
        $class = Inflector::classify($controller);
        $class = __NAMESPACE__  . "\\{$class}Controller";
        switch(true) {
            case !class_exists($class, true):
            case !is_subclass_of($class, __CLASS__):
                $msg = "{$class} does not exist.";
                throw new Exception\NotFoundException($msg);
        }

        return new $class($this->config);
    }

}
