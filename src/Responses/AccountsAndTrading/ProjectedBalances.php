<?php

namespace MichaelDrennen\TDAmeritradeAPI\Responses;

/**
 * Class ProjectedBalance
 * @package MichaelDrennen\TDAmeritradeAPI\Responses
 */
class ProjectedBalances {
    public $availableFunds                   = 0;
    public $availableFundsNonMarginableTrade = 0;
    public $buyingPower                      = 0;
    public $dayTradingBuyingPower            = 0;
    public $dayTradingBuyingPowerCall        = 0;
    public $maintenanceCall                  = 0;
    public $regTCall                         = 0;
    public $isInCall                         = 0;
    public $stockBuyingPower                 = 0;

    public function __construct( array $values ) {
        foreach ( $values as $name => $value ):
            $this->{$name} = (float)$value;
        endforeach;
    }
}