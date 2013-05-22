<?php

namespace Adduc\DomainTracker\Core;

class View {

    protected
        $config;

    public function __construct(Config $config = null) {
        $this->config = $config ?: new Config();
    }

    public function render($view, $view_vars) {

    }

}
