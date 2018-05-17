<?php

use Keros\KerosApp;

require dirname(__FILE__) . '/../vendor/autoload.php';

try {
    $app = (new KerosApp())->get();
    $app->run();
} catch (Exception $e) {
    die("Error running app : " . $e->getMessage());
}