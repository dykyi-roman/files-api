<?php

namespace Dykyi\Exception;

/**
 * Class ClientException
 * @package Dykyi\Exception
 */
class ClientException extends \RuntimeException
{
    public $response;

    /**
     * ClientException constructor.
     * @param string $message
     * @param null $code
     * @param null $response
     */
    public function __construct($message, $code = null, $response = null)
    {
        parent::__construct($message, $code);
        $this->response = $response;
    }

    /**
     * @return null
     */
    public function response()
    {
        return $this->response;
    }

}