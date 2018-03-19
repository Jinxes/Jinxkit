<?php
namespace Jinxkit\Library;

use RuntimeException;


/**
 * @method int $statusCode
 */
class HttpException extends RuntimeException
{
    /** @var int */
    private $statusCode;
    
    public function __construct($statusCode, $message = null, $previous = null, $code = 0)
    {
        $this->statusCode = $statusCode;

        parent::__construct($message, $code, $previous);
    }

    /** @return int */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
