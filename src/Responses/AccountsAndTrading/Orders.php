<?php

namespace MichaelDrennen\TDAmeritradeAPI\Responses;


class Orders {

    public $orders = [];

    public function __construct( array $values ) {
        foreach ( $values as $i => $orderData ):
            $this->orders[] = new Order($orderData);
        endforeach;
    }
}