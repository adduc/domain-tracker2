<?php

namespace Adduc\DomainTracker\Core;
use Adduc\DomainTracker\Exception;

class View {

    protected
        $config;

    public function __construct(Config $config = null) {
        $this->config = $config ?: new Config();
    }

    public function render($view, $view_vars) {
        $path = $this->config['paths.root']
            . "/{$this->config['paths.templates']}/{$view}.php";

        if(file_exists($path)) {
            $v = new Container($view_vars ?: array());
            ob_start();
            include($path);
            $output = ob_get_clean();
            ob_end_clean();
            return $output;
        } else {
            $msg = "View {$view} does not exist (Looked at {$path}).";
            throw new Exception\ViewNotFound($msg);
        }
    }

}
