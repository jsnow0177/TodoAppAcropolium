<?php
namespace Puppy;

use Puppy\DI\IContainer;
use Puppy\Http\IRequest;
use Puppy\Http\IResponse;
use Puppy\Http\NotFoundException;
use Puppy\Http\Response;

/**
 * Class Application
 * @package Puppy
 */
class Application{

    /**
     * @var IContainer
     */
    private $container;

    /**
     * @var array
     */
    private $routes;

    /**
     * Application constructor.
     * @param IContainer $container
     */
    public function __construct(IContainer $container)
    {
        $this->container = $container;
        $this->routes = [];
    }

    /**
     * @return IContainer
     */
    public function GetContainer(): IContainer
    {
        return $this->container;
    }

    /**
     * @param string $name
     * @param string $pattern
     * @param string $targetClass Класс контроллера, который будет вызван
     * @param string $targetAction Действие контроллера
     * @return static
     * @throws \InvalidArgumentException
     */
    public function Route(string $name, string $pattern, string $targetClass, string $targetAction): Application
    {
        if(!isset($this->routes[$name])){
            $this->routes[$name] = [
                'pattern' => $pattern,
                'targetClass' => $targetClass,
                'targetAction' => $targetAction
            ];
        }

        if(!class_exists($targetClass))
            throw new \InvalidArgumentException("Invalid classname {$targetClass}");

        $this->routes[$name]['pattern'] = $pattern;
        $this->routes[$name]['targetClass'] = $targetClass;
        $this->routes[$name]['targetAction'] = $targetAction;

        return $this;
    }

    /**
     * Обрабатывает запрос
     * @param IRequest $request
     * @return IResponse
     */
    public function Handle(IRequest $request): IResponse
    {
        foreach($this->routes as $route){
            ['pattern' => $pattern, 'targetClass' => $targetClass, 'targetAction' => $targetAction] = $route;

            if(preg_match($pattern, $request->GetUri()->GetPath(), $matches) != false){
                if($targetAction === '%action%' && isset($matches['action'])) {
                    $targetAction = $matches['action'];
                }

                if($targetAction === '%action%')
                    $targetAction = '';

                $response = null;

                try {
                    $response = $this->executeController($targetClass, $targetAction, $request);
                }catch(NotFoundException $ex){}

                return is_null($response) ? $this->error404() : $response;
            }
        }

        return $this->error404();
    }

    /**
     * @param string $targetClass
     * @param string $targetAction
     * @param IRequest $request
     * @return IResponse
     */
    protected function executeController(string $targetClass, string $targetAction, IRequest $request): IResponse
    {
        /** @var AbstractController $controller */
        $controller = new $targetClass($this);
        $response = $controller->Handle($request, $targetAction);
        return $response;
    }

    /**
     * @return IResponse
     */
    protected function error404(): IResponse
    {
        return (new Response())
            ->WithBody('<h1>404 Not Found</h1>');
    }

}