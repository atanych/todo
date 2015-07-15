<?php
namespace TD\Controllers;

use TD\Engine\Logger;

/**
 * Created by PhpStorm
 */
class TaskController extends Controller
{

    public function beforeAction()
    {
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