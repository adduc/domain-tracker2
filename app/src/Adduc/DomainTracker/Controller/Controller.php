<?php
namespace Adduc\DomainTracker\Controller;
use Doctrine\Common\Inflector\Inflector;
use Adduc\DomainTracker\Exception;

abstract class Controller {

    protected
        $config,
        $view,
        $layout,
        $viewVars;

    public function __construct(array &$config = array()) {
        $this->config = $config;
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

}
