<?php

namespace MichaelDrennen\TDAmeritradeAPI\Exceptions;

use Throwable;

class BaseServerException extends \Exception {

    public $meta = [];
    public function __construct( $message = "", $code = 0, Throwable $previous = NULL, array $meta = [] ) {
        parent::__construct( $message, $code, $previous );
        $this->meta = $meta;
    }
}
