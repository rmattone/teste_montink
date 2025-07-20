<?php
namespace app\exceptions;

use core\QueryLogger;

class DBException extends \Exception
{
    public function __construct($message = "Database error", $sql = "", $params = [], $executionTime = 0, $code, \Exception $previous = null)
    {
        QueryLogger::logQuery($sql, $params, $executionTime, ['message' => $message], 'ERROR');
        parent::__construct($message, $code, $previous);
    }
}
