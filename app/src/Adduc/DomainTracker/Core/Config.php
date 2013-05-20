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
        $config = &$this->config;
        $key = explode('.', $key);
        while($key) {
            var_dump($key);
            exit;
        }
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
