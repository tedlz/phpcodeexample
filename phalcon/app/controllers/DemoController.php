<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller;

class DemoController extends Controller
{
    public function indexAction()
    {
        $this->cookies->set('a', 1);
        echo 'PHPSESSID: ' . $this->cookies->get('PHPSESSID');
    }
}
