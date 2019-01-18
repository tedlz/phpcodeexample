<?php

namespace App\Controllers;

use App\Library\GeneralLevel;
use Phalcon\Mvc\Controller;

class LevelController extends Controller
{
    public function indexAction()
    {
        $exp = [
            795923,
        ];

        foreach ($exp as $v) {
            echo GeneralLevel::getLevel($v), PHP_EOL;
        }
    }
}
