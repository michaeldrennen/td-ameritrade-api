<?php

namespace MichaelDrennen\TDAmeritradeAPI\Responses\AccountsAndTrading;


class Position {

    public $averagePrice;                       // Ex: 0.09
    public $currentDayProfitLoss;               // Ex: 0
    public $currentDayProfitLossPercentage;     // Ex: 0
    public $longQuantity;                       // Ex: 1
    public $settledLongQuantity;                // Ex: 1
    public $settledShortQuantity;               // Ex: 0


    /**
     * Array
     * (
     * [assetType] => EQUITY
     * [cusip] => 205750201
     * [symbol] => LODE
     * )
     */
    public $instrument = [];

    public $marketValue;

    public function __construct( array $positionData ) {
        $this->averagePrice                   = (float)$positionData[ 'averagePrice' ];
        $this->currentDayProfitLoss           = $positionData[ 'currentDayProfitLoss' ];
        $this->currentDayProfitLossPercentage = $positionData[ 'currentDayProfitLossPercentage' ];
        $this->longQuantity                   = $positionData[ 'longQuantity' ];
        $this->settledLongQuantity            = $positionData[ 'settledLongQuantity' ];
        $this->settledShortQuantity           = $positionData[ 'settledShortQuantity' ];
        $this->instrument                     = [
            'assetType' => $positionData[ 'instrument' ][ 'assetType' ],
            'cusip'     => $positionData[ 'instrument' ][ 'cusip' ],
            'symbol'    => $positionData[ 'instrument' ][ 'symbol' ],
        ];
        $this->marketValue                    = (float)$positionData[ 'marketValue' ];
    }
}