<?php

namespace Adduc\DomainTracker\Controller;
use Adduc\DomainTracker\Model;

class IndexController extends Controller {

    public function indexAction() {
        $user = $this->getModel('User');
        if($user->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
    }

}
