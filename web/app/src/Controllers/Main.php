<?php
namespace TodoApp\Controllers;

use Puppy\Http\IRequest;
use TodoApp\Controller;

/**
 * Class Main
 * @package TodoApp\Controllers
 */
class Main extends Controller{

    /**
     * @action index
     * @param IRequest $request
     */
    protected function Index(IRequest $request)
    {
        $view = $this->view('main/index');
        $view
            ->AddPartial('header', $this->view('header'))
            ->AddPartial('footer', $this->view('footer'));

        return $view;
    }

}