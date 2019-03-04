<?php
namespace Puppy;

use Puppy\Http\IRequest;
use Puppy\Http\IResponse;
use Puppy\Http\NotFoundException;
use Puppy\Http\Response;

/**
 * Class Controller
 * @package Puppy
 */
abstract class AbstractController{

    /**
     * @var Application
     */
    private $application;

    /**
     * @var array
     */
    private $actionsList;

    private const ANNOTATION_ACTION_PATTERN = '/@action\s+(?<action>[A-z0-9_\-]+)/';

    /**
     * Controller constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
        $this->actionsList = [];

        $this->loadActions();
    }

    /**
     * Загружает список действий класса
     */
    private function loadActions(){
        $reflection = new \ReflectionClass($this);
        $methods = $reflection->getMethods(
            \ReflectionMethod::IS_PUBLIC|
            \ReflectionMethod::IS_PROTECTED
        );

        foreach($methods as $reflectionMethod) {
            /** @var \ReflectionMethod $reflectionMethod */
            $docComment = $reflectionMethod->getDocComment();
            $matches = [];
            if (preg_match(self::ANNOTATION_ACTION_PATTERN, $docComment, $matches) != false) {
                $this->actionsList[$matches['action']] = $reflectionMethod->getName();
            }
        }
    }

    /**
     * @return Application
     */
    public function App(): Application
    {
        return $this->application;
    }

    /**
     * @param IRequest $request
     * @param string $action
     * @return IResponse
     * @throws NotFoundException
     */
    public function Handle(IRequest $request, string $action): IResponse
    {
        if(!isset($this->actionsList[$action]))
            throw new NotFoundException();

        $result = $this->{$this->actionsList[$action]}($request);

        if(is_scalar($result) || (is_object($result) && method_exists($result, '__toString'))){
            return (new Response())
                ->WithBody((string)$result);
        }

        if(is_object($result) && ($result instanceof View)){
            /** @var View $result */
            return (new Response())
                ->WithBody($result->Render());
        }

        return new Response();
    }

}