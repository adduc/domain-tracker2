<?php

namespace Adduc\DomainTracker\Core;

class Container implements \ArrayAccess {

    protected $data = null;

    public function offsetExists($offset) {
        return is_null($this->offsetGet($offset));
    }

    public function offsetGet($offset) {
        $current_value = $this->data;
        $path = explode('.', $offset);
        while($path) {
            $current_offset = trim(array_shift($path));
            switch(true) {
                case !is_array($current_value):
                case !isset($current_value[$current_offset]):
                    return null;
                default:
                    $current_value = $current_value[$current_offset];
            }
        }
        return $current_value;
    }

    public function offsetSet($offset, $value = null) {
        if(is_array($offset)) {
            foreach($offset as $key => $value) {
                $this->offsetSet($key, $value);
            }
        }

        $current_value = &$this->data;
        $path = explode('.', $offset);

        while($path) {
            $current_offset = trim(array_shift($path));
            switch(true) {
                case !is_array($current_value):
                case !isset($current_value[$current_offset]):
                    $current_value = array();
                    break;
                default:
                    $current_value = &$current_value[$current_offset];
            }
        }

        $current_value = $value;
    }

    public function offsetUnset($offset) {
        $this->set($offset, null);
    }

}
