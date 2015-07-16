<?php
namespace TD\Controllers;

use TD\Engine\APIHelper;
use TD\Engine\Application;
use TD\Engine\Auth;
use TD\Exceptions\ApplicationException;
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
        Auth::check($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
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
        $task->parseDeadline();

        if ($task->validate()) {
            $task->save();
            $this->render('task', ['task' => $task]);
        } else {
            APIHelper::error(400, $task->getErrors());
        }
    }

    /**
     * Редактирует запись
     *
     * @throws ApplicationException
     */
    public function actionEdit()
    {
        /**
         * @var Task $task
         */
        $task = Application::$db->findByPk('task', $_POST['Task']['id']);
        if (empty($task)) {
            throw new ApplicationException(404);
        }
        $task->setAttributes($_POST['Task']);
        $task->parseDeadline();
        if ($task->validate()) {
            $task->update();
            $this->render('task', ['task' => $task]);
        } else {
            APIHelper::error(400, $task->getErrors());
        }
    }

    /**
     * Удаляет запись
     */
    public function actionRemove()
    {
        Application::$db->remove('task', $_POST['id']);
        APIHelper::success();
    }
}