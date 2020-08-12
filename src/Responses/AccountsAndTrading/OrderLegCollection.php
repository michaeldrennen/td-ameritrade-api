<?php

namespace MichaelDrennen\TDAmeritradeAPI\Responses\AccountsAndTrading;


class OrderLegCollection {

    public $orderLegType;   // Ex: EQUITY
    public $legId;          // Ex: 1
    public $instrument;     //
    public $instruction;    // Ex: BUY
    public $positionEffect; // Ex: OPENING
    public $quantity;       // Ex: 1


    public function __construct( array $orderLegCollectionData ) {
        $this->orderLegType   = $orderLegCollectionData[ 'orderLegType' ];
        $this->legId          = $orderLegCollectionData[ 'legId' ];
        $this->instrument     = [
            'assetType' => $orderLegCollectionData[ 'instrument' ][ 'assetType' ] ?? NULL,
            'cusip'     => $orderLegCollectionData[ 'instrument' ][ 'cusip' ] ?? NULL,
            'symbol'    => $orderLegCollectionData[ 'instrument' ][ 'symbol' ] ?? NULL,
        ];
        $this->instruction    = $orderLegCollectionData[ 'instruction' ];
        $this->positionEffect = $orderLegCollectionData[ 'positionEffect' ];
        $this->quantity       = $orderLegCollectionData[ 'quantity' ];
    }
}