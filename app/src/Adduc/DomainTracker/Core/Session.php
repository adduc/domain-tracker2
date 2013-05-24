<?php

class Session {

    public static function getInstance() {
        static $instance = new Session();
        return $instance;
    }

    protected function __construct() {
        session_start();
    }

}
