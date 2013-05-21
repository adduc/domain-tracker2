<?php

namespace Adduc\DomainTracker\Core;

class Config {

    protected $config = array(
        'paths' => array(
            'root' => false,
            'url' => false
        ),
        'errors' => array(
            'display' => true,
            'level' => -1,
            'verbose' => true
        ),
        'pdo' => array(
            'dsn' => '',
            'username' => '',
            'password' => ''
        )
    );

    public function loadFile($file) {
        $config = parse_ini_file($file, true);
        if(!$config) {
            $msg = "Config file doesn't exist or is not readable.";
            throw new Exception\Ex500($msg);
        }

        $this->set($config);
    }

    /**
     * Set config setting
     *
     * @example set('errors.display', true)
     *
     * @throws InvalidArgumentException thrown if setting does not exist.
     * @param array|string $key If array, combines current settings with
     *        array values. If string, set setting with provided $value.
     * @return void
     */
    public function set($key, $value = null) {
        if(is_array($key)) {
            foreach($key as $k2 => $v2) {
                $this->set($k2, $v2);
            }
            return;
        }

        // Explode by dot (.) to walk to config setting.
        $current_value = &$this->config;
        $path = explode('.', $key);
        while($path) {
            $current_key = array_shift($path);
            if(!isset($current_value[$current_key])) {
                throw new \InvalidArgumentException("Setting {$key} does not exist.");
            }
            $current_value = &$current_value[$current_key];
        }

        $current_value = $value;
    }

    /**
     * Retrieve config setting
     *
     * @param string $key
     * @return mixed|null Returns config value on true, null on failure
     */
    public function get($key) {

    }

}
