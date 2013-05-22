<?php

namespace Adduc\DomainTracker\Core;

class Config extends Container {

    public function __construct($file = null) {
        is_null($file) || $this->loadFile($file);
    }

    public function loadFile($file) {
        $data = parse_ini_file($file, true);
        if(!$data) {
            $msg = "Config file doesn't exist or is not readable.";
            throw new Exception\Ex500($msg);
        }

        $this->data = $data;
    }

}
