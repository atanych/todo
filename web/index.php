<?php
use TD\Engine\Application;

require_once "../vendor/autoload.php";
$config = require_once('../src/Config/main.php');

Application::run($config);
