<?php
namespace TD\Controllers;

use TD\Engine\APIHelper;
use TD\Engine\Application;
use TD\Engine\Auth;
use TD\Models\Task;

/**
 * Created by PhpStorm
 */
class TaskController extends Controller
{

    public function beforeAction()
    {
        return parent::beforeAction();
    }

    /**
     * Индексная страница
     *
     * @throws \TD\Exceptions\ApplicationException
     */
    public function actionIndex()
    {
        if (!Auth::identify($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
            header('WWW-Authenticate: Basic realm="Bad auth"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'You press cancel';
            exit;
        }
        $this->render('index', [
            'tasks' => Application::$db->findAll('task')
        ]);
    }

    /**
     * Создает запись
     *
     * @throws \TD\Exceptions\ApplicationException
     */
    public function actionCreate()
    {
        $task = new Task();
        $task->setAttributes($_POST['Task']);
        if ($task->validate()) {
            $task->save();
            APIHelper::success($task);
        } else {
            APIHelper::error(400, $task->getErrors());
        }
    }
}