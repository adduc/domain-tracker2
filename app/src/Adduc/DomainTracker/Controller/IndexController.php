<?php

namespace Adduc\DomainTracker\Controller;

class IndexController extends Controller {

    public function indexAction() {
        switch(true) {
            case !isset($_COOKIE[$this->config['cookie_name']]):
            case !$_COOKIE[$this->config['cookie_name']]:
                break;
            default:
                // Check Login to see if we need to redirect.
        }
    }

}
