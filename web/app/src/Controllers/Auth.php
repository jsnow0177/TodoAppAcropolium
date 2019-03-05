<?php
namespace TodoApp\Controllers;

use Puppy\Http\IMessage;
use Puppy\Http\IRequest;
use Puppy\Http\IResponse;
use Puppy\Http\Response;
use Puppy\View;
use TodoApp\Controller;
use TodoApp\ModelException;
use TodoApp\Models\Users;

class Auth extends Controller{

    /**
     * @action login
     * @param IRequest $request
     * @return View|IMessage
     */
    public function Login(IRequest $request){
        if(isset($_SESSION['authorized']))
            return (new Response())
                ->WithHeader('Location', '/');

        $view = $this->view('auth/login');
        $view->AddPartial('header', $this->view('header'));
        $view->AddPartial('footer', $this->view('footer'));

        return $view;
    }

    /**
     * @action logout
     * @param IRequest $request
     * @return IMessage
     */
    public function Logout(IRequest $request){
        if(isset($_SESSION['authorized'])){
            unset($_SESSION['authorized'], $_SESSION['user_id']);
        }

        return (new Response())
            ->WithHeader('Location', '/auth/login');
    }

    /**
     * @action register
     * @param IRequest $request
     * @return View|IMessage
     */
    public function Register(IRequest $request)
    {
        if(isset($_SESSION['authorized']))
            return (new Response())
                ->WithHeader('Location', '/');

        $view = $this->view('auth/register');
        $view->AddPartial('header', $this->view('header'));
        $view->AddPartial('footer', $this->view('footer'));

        return $view;
    }

    /**
     * @action ajax_login
     * @param IRequest $request
     * @return IResponse
     */
    public function AjaxLogin(IRequest $request){
        $post = $request->GetPostParams();

        if(!isset($post['login']) || !isset($post['pass']))
            return $this->jsonError('Не указан логин или пароль!');

        if(filter_var($post['login'], FILTER_VALIDATE_EMAIL) === false)
            return $this->jsonError('Указан некорректный адрес электронной почты!');

        if(mb_strlen($post['pass'], 'utf-8') < 5)
            return $this->jsonError('Указан слишком короткий пароль!');

        /** @var Users $users */
        $users = $this->model(Users::class);
        $isAuthorized = $users->Confirm($post['login'], $post['pass']);

        if(!$isAuthorized)
            return $this->jsonError('Не удалось авторизовать!');

        $user = $users->Get($post['login']);

        if(is_null($user))
            return $this->jsonError('Произошла неожиданная ошибка!');

        $_SESSION['authorized'] = true;
        $_SESSION['user_id'] = $user['user_id'];

        return $this->jsonSuccess(['user_id' => $user['user_id']]);
    }

    /**
     * @action ajax_register
     * @param IRequest $request
     * @return IResponse
     */
    public function AjaxRegister(IRequest $request){
        $post = $request->GetPostParams();

        if(!isset($post['login']) || !isset($post['pass']))
            return $this->jsonError('Не указан логин или пароль!');

        if(filter_var($post['login'], FILTER_VALIDATE_EMAIL) === false)
            return $this->jsonError('Указан некорректный адрес электронной почты!');

        if(mb_strlen($post['pass'], 'utf-8') < 5)
            return $this->jsonError('Указан слишком короткий пароль!');

        /** @var Users $users */
        $users = $this->model(Users::class);

        try{
            $user = $users->Add($post['login'], $post['pass']);
            return $this->jsonSuccess(['user_id' => $user['user_id']]);
        }catch(\Exception $ex){
            return $this->jsonError('Не удалось создать нового пользователя!');
        }

    }

}