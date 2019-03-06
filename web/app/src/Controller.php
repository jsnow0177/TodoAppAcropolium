<?php
namespace TodoApp;

use Puppy\AbstractController;
use Puppy\AbstractModel;
use Puppy\Application;
use Puppy\Http\IRequest;
use Puppy\Http\IResponse;
use Puppy\Http\NotFoundException;
use Puppy\Http\Response;
use Puppy\ModelFactory;
use Puppy\Storing\Config;
use Puppy\View;
use Puppy\ViewFactory;

/**
 * Class Controller
 * @package TodoApp
 */
class Controller extends AbstractController{

    /**
     * @var ViewFactory
     */
    protected $viewFactory;

    /**
     * @var ModelFactory
     */
    protected $modelFactory;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var bool
     */
    private $propsInitialized = false;

    /**
     * Controller constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        parent::__construct($application);
    }

    /**
     * @param IRequest $request
     * @param string $action
     * @return IResponse
     * @throws NotFoundException
     */
    public function Handle(IRequest $request, string $action): IResponse
    {
        $this->initializeProps();
        return parent::Handle($request, $action);
    }

    /**
     *
     */
    private function initializeProps(){
        if($this->propsInitialized)
            return;

        $container = $this->App()->GetContainer();
        $this->viewFactory = $container->Get('viewFactory');
        $this->modelFactory = $container->Get('modelFactory');
        $this->config = $container->Get('config');

        $this->propsInitialized = true;
    }

    /**
     * @param string $template
     * @return View
     */
    protected function view(string $template): View
    {
        return $this->viewFactory->Create($template);
    }

    /**
     * @param string $modelClass
     * @return AbstractModel
     * @throws \InvalidArgumentException
     */
    protected function model(string $modelClass): AbstractModel
    {
        return $this->modelFactory->Create($modelClass);
    }

    /**
     * @param array $data
     * @return IResponse
     */
    protected function jsonSuccess(array $data): IResponse
    {
        return $this->json(['response' => $data]);
    }

    /**
     * @param string $message
     * @param int $code
     * @param array|null $additionalData
     * @return IResponse
     */
    protected function jsonError(string $message, int $code = 0, ?array $additionalData = null): IResponse
    {
        $error = ['code' => $code, 'message' => $message];

        if(!is_null($additionalData))
            $error['data'] = $additionalData;

        return $this->json(['error' => $error]);
    }

    /**
     * @param array $data
     * @return IResponse
     */
    protected function json(array $data): IResponse
    {
        $json = json_encode($data);
        $jsonLength = mb_strlen($json, '8bit');

        /** @var Response $response */
        $response = (new Response())
            ->WithBody($json)
            ->WithHeader('Content-Type', 'application/json')
            ->WithHeader('Content-Length', $jsonLength);

        return $response;
    }

    /**
     * @param string $location
     * @return IResponse
     */
    protected function redirect(string $location): IResponse{
        /** @var IResponse $response */
        $response = (new Response())->WithHeader('Location', $location);
        return $response;
    }

}