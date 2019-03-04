<?php
namespace TodoApp;

use Throwable;

class ModelException extends \Exception{

    /**
     * Construct the exception. Note: The message is NOT binary safe.
     * @link https://php.net/manual/en/exception.construct.php
     * @param string $message [optional] The Exception message to throw.
     * @param int $code [optional] The Exception code.
     * @since 5.1.0
     */
    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, $code, null);
    }

}