<?php
namespace Puppy;

/**
 * Class View
 * @package Puppy
 */
class View{

    /**
     * @var string Путь к файлу представления
     */
    private $viewPath;

    /**
     * @var array Хранилище переменных представления
     */
    private $viewBag;

    /**
     * @var View[]
     */
    private $partials;

    /**
     * View constructor.
     * @param string $viewPath
     */
    public function __construct(string $viewPath)
    {
        if(!file_exists($viewPath))
            throw new \InvalidArgumentException("View {$viewPath} does not exists");

        $this->viewPath = $viewPath;
        $this->viewBag = [];
    }

    /**
     * @param string $key
     * @param $value
     * @return View
     */
    public function Set(string $key, $value): View
    {
        $this->viewBag[$key] = $value;

        foreach($this->partials as $partial)
            $partial->Set($key, $value);

        return $this;
    }

    /**
     * @param string $key
     * @return View
     */
    public function Del(string $key): View
    {
        unset($this->viewBag[$key]);

        return $this;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function Has(string $key): bool
    {
        return isset($this->viewBag[$key]);
    }

    /**
     * @param array $variables
     * @return View
     */
    public function Assign(array $variables): View
    {
        foreach($variables as $k => $v)
            $this->viewBag[$k] = $v;

        foreach($this->partials as $partial)
            $partial->Assign($variables);

        return $this;
    }

    /**
     * @param string $name
     * @param View $view
     * @return View
     */
    public function AddPartial(string $name, View $view): View
    {
        $view->Assign($this->viewBag);
        $this->partials[$name] = $view;

        return $this;
    }

    /**
     * @param string $name
     */
    protected function section(string $name)
    {
        if(isset($this->partials[$name])){
            echo $this->partials[$name]->Render();
        }else{
            echo "[Partial \"{$name}\" not found]";
        }
    }

    /**
     * @return string
     */
    public function Render(): string
    {
        ob_start();
        extract($this->viewBag);
        include($this->viewPath);
        $rendered = ob_get_clean();

        return $rendered;
    }

}