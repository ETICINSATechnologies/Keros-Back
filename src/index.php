<?php

use Keros\KerosApp;

require dirname(__FILE__) . '/../vendor/autoload.php';

$app = (new KerosApp())->getApp();
$app->run();
