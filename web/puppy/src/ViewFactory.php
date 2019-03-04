<?php
namespace Puppy;

/**
 * Class ViewFactory
 * @package Puppy
 */
class ViewFactory{

    /**
     * @var string
     */
    private $viewsPath;

    /**
     * ViewFactory constructor.
     * @param string $viewsPath
     */
    public function __construct(string $viewsPath)
    {
        $this->viewsPath = $viewsPath;
    }

    /**
     * @param string $template
     * @return View
     */
    public function Create(string $template): View
    {
        return (new View($this->viewsPath . '/' . $template . '.php'));
    }

}