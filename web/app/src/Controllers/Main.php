<?php
namespace TodoApp\Controllers;

use Puppy\Http\IRequest;
use Puppy\Http\Response;
use Puppy\View;
use TodoApp\Controller;
use TodoApp\Models\Users;

/**
 * Class Main
 * @package TodoApp\Controllers
 */
class Main extends Controller{

    /**
     * @action index
     * @param IRequest $request
     * @return View
     */
    protected function Index(IRequest $request)
    {
        if(!isset($_SESSION['authorized']))
            return (new Response())->WithHeader('Location', '/auth/login');

        /** @var Users $users */
        $users = $this->model(Users::class);

        $view = $this->view('main/index');
        $view
            ->AddPartial('header', $this->view('header'))
            ->AddPartial('footer', $this->view('footer'));

        return $view;
    }

}