<?php
namespace app\exceptions;

class ValidationException extends \Exception {
    protected $code = 422;
}