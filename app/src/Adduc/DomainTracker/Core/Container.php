<?php

namespace Adduc\DomainTracker\Core;

class Container implements \ArrayAccess {

    protected $data = array();

    public function __construct(array $data = null) {
        is_array($data) && $this->offsetSet(null, $data);
    }

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

    public function offsetSet($offset, $value) {
        if(is_array($value)) {
            foreach($value as $o2 => $v2) {
                $o2 = $offset ? "{$offset}.{$o2}" : "{$o2}";
                $this->offsetSet($o2, $v2);
            }
            return;
        }

        $current_value = &$this->data;
        $path = explode('.', $offset);

        while($path) {

            $current_offset = trim(array_shift($path));
            switch(true) {
                case !is_array($current_value):
                    $current_value = array();
                    break;
                case !isset($current_value[$current_offset]):
                    $current_value[$current_offset] = array();
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
