<?php

namespace MichaelDrennen\TDAmeritradeAPI\Responses;


use Carbon\Carbon;

class Order {

    public $session;                    // Ex: NORMAL
    public $duration;                   // Ex: DAY
    public $orderType;                  // Ex: MARKET
    public $complexOrderStrategyType;   // Ex: NONE
    public $quantity;                   // Ex: 1
    public $filledQuantity;             // Ex: 0
    public $remainingQuantity;          // Ex: 1
    public $requestedDestination;       // Ex: AUTO
    public $destinationLinkName;        // Ex: AutoRoute

    public $orderStrategyType;  // Ex: SINGLE
    public $orderId;            // Ex: 2371836482
    public $cancelable;         // Ex: 1
    public $editable;           // Ex:
    public $status;             // Ex: QUEUED
    public $enteredTime;        // Ex: 2019-11-17T23:19:39+0000
    public $accountId;          // Ex: 686195796

    public $orderLegCollection = [];


    public function __construct( array $orderData ) {

        $this->session                  = $orderData[ 'session' ];
        $this->duration                 = $orderData[ 'duration' ];
        $this->orderType                = $orderData[ 'orderType' ];
        $this->complexOrderStrategyType = $orderData[ 'complexOrderStrategyType' ];
        $this->quantity                 = $orderData[ 'quantity' ];
        $this->filledQuantity           = $orderData[ 'filledQuantity' ];
        $this->remainingQuantity        = $orderData[ 'remainingQuantity' ];
        $this->requestedDestination     = $orderData[ 'requestedDestination' ];
        $this->destinationLinkName      = $orderData[ 'destinationLinkName' ];

        $this->orderStrategyType = $orderData[ 'orderStrategyType' ];
        $this->orderId           = $orderData[ 'orderId' ];
        $this->cancelable        = $orderData[ 'cancelable' ];
        $this->editable          = $orderData[ 'editable' ];
        $this->status            = $orderData[ 'status' ];
        $this->enteredTime       = Carbon::parse( $orderData[ 'enteredTime' ] );
        $this->accountId         = $orderData[ 'accountId' ];

        foreach ( $orderData[ 'orderLegCollection' ] as $orderLegCollectionData ):
            $this->orderLegCollection[] = new OrderLegCollection( $orderLegCollectionData );
        endforeach;
    }
}