<?php
namespace TD\Controllers;

use TD\Engine\Auth;
use TD\Engine\Logger;

/**
 * Created by PhpStorm
 */
class TaskController extends Controller
{

    public function beforeAction()
    {
        if (!Auth::identify($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
            header('WWW-Authenticate: Basic realm="Bad auth"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'You press cancel';
            exit;
        }
        return parent::beforeAction();
    }

    public function actionIndex()
    {
        echo 'INDEx';
    }

    public function actionCreate()
    {
        echo 'CREATED';
    }
}