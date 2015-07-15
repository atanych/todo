<?php
use TD\Engine\Application;

require_once "../vendor/autoload.php";
$config = require_once('src/config/main.php');

(new Application($config))->run();
