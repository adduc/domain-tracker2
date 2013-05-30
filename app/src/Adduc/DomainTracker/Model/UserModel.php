<?php

namespace Adduc\DomainTracker\Model;
use Adduc\DomainTracker\Exception;

class UserModel extends Model {

    public function isLoggedIn() {
        $cookie = $this->config['cookie_name'];
        if(!$cookie) {
            throw new Exception\InvalidConfig();
        } elseif(!isset($_COOKIE[$cookie]) || !$_COOKIE[$cookie]) {
            return false;
        }

        return true;
    }

}
