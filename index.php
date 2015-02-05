<?php

header("Content-Type: text/html; charset=UTF-8");
require("Lib/Hana/Timer.php");
$timer = new Hana_Timer();
$timer->set();

error_reporting(E_ALL);

require('Lib/Hana/Application.php');
$application = new Hana_Application();
// $application->appPath(ROOT);
$application->run();


// var_dump(ROOT,APP,BASE);


$timer->set();
var_dump($timer->getTime(),memory_get_usage());