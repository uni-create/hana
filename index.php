<?php
require('Lib/Hana/Application.php');
$application = new Hana_Application();
$application->appPath(ROOT);
$application->run();