<?php
namespace Adduc\DomainTracker\Controller;
use Adduc\DomainTracker\Exception;
use Adduc\DomainTracker\Core;
use Doctrine\Common\Inflector\Inflector;

class Controller {

    protected
        $config,
        $view,
        $layout,
        $view_vars;

    public function __construct(Core\Config $config = null) {
        $this->config = $config ?: new Core\Config();
    }

    public function run($action, array $params) {
        $action = Inflector::camelize($action) . "Action";
        if(!method_exists($this, $action)) {
            throw new Exception\Ex404("{$action} does not exist.");
        }

        $class = Inflector::tableize(substr(get_called_class(),
            strlen(__NAMESPACE__) + 1, -strlen('Controller')));

        $this->view = "{$class}/{$action}";
        $this->layout = "layout/default";

        $this->$action();
        $this->render();
    }

    public function render($view = null, $layout = null) {
        is_null($view) && $view = $this->view;
        is_null($layout) && $layout = $this->layout;

        $vc = new Core\View($this->config);
        $output = $view
            ? $vc->render($view, $this->view_vars)
            : null;
        $output = $layout
            ? $vc->render($layout, $this->view_vars, $output)
            : $output;

        echo $output;
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
