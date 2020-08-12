<?php

namespace MichaelDrennen\TDAmeritradeAPI\Responses\AccountsAndTrading;

class Positions {

    public $positions = [];

    public function __construct( array $values ) {
        foreach ( $values as $i => $positionData ):
            $this->positions[] = new Position($positionData);
        endforeach;
    }
}