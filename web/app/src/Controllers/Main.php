<?php
namespace TodoApp\Controllers;

use Puppy\Http\IMessage;
use Puppy\Http\IRequest;
use Puppy\Http\IResponse;
use Puppy\Http\Response;
use Puppy\View;
use TodoApp\Controller;
use TodoApp\ModelException;
use TodoApp\Models\Tasks;
use TodoApp\Models\Users;

/**
 * Class Main
 * @package TodoApp\Controllers
 */
class Main extends Controller{

    private $newTaskAllowedStatuses = [
        'new' => 'Новая задача',
        'active' => 'Активная задача'
    ];

    private $editTaskAllowedStatuses = [
        'new' => 'Новая задача',
        'active' => 'Активная задача',
        'done' => 'Выполненная задача'
    ];

    /**
     * @action index
     * @param IRequest $request
     * @return View|IMessage
     */
    protected function Add(IRequest $request)
    {
        if(!isset($_SESSION['authorized']))
            return $this->redirect('/auth/login');

        /** @var Users $users */
        $users = $this->model(Users::class);

        $view = $this->view('main/task')
            ->Assign([
                'ajax_action' => '/task.add',
                'task_id' => 0,
                'title' => '',
                'body' => '',
                'status' => 'new',
                'buttonText' => 'Создать задачу',
                'validateOnInit' => false,
                'allowedStatuses' => $this->newTaskAllowedStatuses
            ]);

        $view
            ->AddPartial('header', $this->view('header'))
            ->AddPartial('footer', $this->view('footer'));

        return $view;
    }

    /**
     * @action edit
     * @param IRequest $request
     * @return IMessage|View
     */
    protected function Edit(IRequest $request){
        if(!isset($_SESSION['authorized']))
            return $this->redirect('/auth/login');

        $get = $request->GetQueryParams();
        if(!isset($get['task_id']) || !is_numeric($get['task_id']))
            return $this->redirect('/');

        /** @var Tasks $tasks */
        $tasks = $this->model(Tasks::class);

        $task = $tasks->Get((int)$_SESSION['user_id'], (int)$get['task_id']);
        if(is_null($task))
            return $this->redirect('/');

        $view = $this->view('main/task')
            ->Assign([
                'ajax_action' => '/task.edit',
                'task_id' => $task['task_id'],
                'title' => html_entity_decode($task['title']),
                'body' => html_entity_decode($task['body']),
                'status' => $task['status'],
                'buttonText' => 'Обновить задачу',
                'validateOnInit' => true,
                'allowedStatuses' => $this->editTaskAllowedStatuses
            ]);

        $view
            ->AddPartial('header', $this->view('header'))
            ->AddPartial('footer', $this->view('footer'));

        return $view;
    }

    /**
     * @action list
     * @param IRequest $request
     * @return View|IResponse
     */
    public function List(IRequest $request){
        if(!isset($_SESSION['authorized']))
            return $this->redirect('/auth/login');


    }

    /**
     * @action task.add
     * @param IRequest $request
     * @return IResponse
     */
    protected function TaskAdd(IRequest $request){
        if(!isset($_SESSION['authorized']))
            return $this->jsonError('Для того, чтобы сохранить задачу необходимо авторизоваться', 1);

        $post = $request->GetPostParams();

        if(!isset($post['title']))
            return $this->jsonError('Необходимо указать название задачи!', 2);

        if(!isset($post['status']))
            return $this->jsonError('Необходимо указать статус задачи!', 3);

        if(!in_array($post['status'], array_keys($this->newTaskAllowedStatuses)))
            return $this->jsonError('Указан некорректный статус задачи!', 4);

        ['title' => $title, 'status' => $status] = $post;
        $body = isset($post['body']) ? $post['body'] : '';

        /** @var Tasks $tasks */
        $tasks = $this->model(Tasks::class);

        try{
            $task = $tasks->Create((int)$_SESSION['user_id'], $title, $body, $status);
            return $this->jsonSuccess($task);
        }catch(ModelException $ex){
            return $this->jsonError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * @action task.edit
     * @param IRequest $request
     * @return IResponse
     */
    protected function TaskEdit(IRequest $request){
        if(!isset($_SESSION['authorized']))
            return $this->jsonError('Для того, чтобы сохранить задачу необходимо авторизоваться', 1);

        $post = $request->GetPostParams();

        if(!isset($post['task_id']) || !is_numeric($post['task_id']))
            return $this->jsonError('Не удалось идентифицировать задачу', 5);

        if(!isset($post['title']))
            return $this->jsonError('Необходимо указать название задачи!', 2);

        if(!isset($post['status']))
            return $this->jsonError('Необходимо указать статус задачи!', 3);

        if(!in_array($post['status'], array_keys($this->editTaskAllowedStatuses)))
            return $this->jsonError('Указан некорректный статус задачи!', 4);

        ['title' => $title, 'status' => $status] = $post;
        $body = isset($post['body']) ? $post['body'] : '';

        /** @var Tasks $tasks */
        $tasks = $this->model(Tasks::class);

        try{
            $task = $tasks->Update((int)$_SESSION['user_id'], (int)$post['task_id'], $title, $body, $status);
            return $this->jsonSuccess($task);
        }catch(ModelException $ex){
            return $this->jsonError($ex->getMessage(), $ex->getCode());
        }
    }

}