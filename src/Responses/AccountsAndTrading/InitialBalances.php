<?php

namespace MichaelDrennen\TDAmeritradeAPI\Responses\AccountsAndTrading;

/**
 * Class InitialBalance
 * @package MichaelDrennen\TDAmeritradeAPI\Responses
 */
class InitialBalances {
    public $accruedInterest                  = 0;
    public $availableFundsNonMarginableTrade = 0;
    public $bondValue                        = 0;
    public $buyingPower                      = 0;
    public $cashBalance                      = 0;
    public $cashAvailableForTrading          = 0;
    public $cashReceipts                     = 0;
    public $dayTradingBuyingPower            = 0;
    public $dayTradingBuyingPowerCall        = 0;
    public $dayTradingEquityCall             = 0;
    public $equity                           = 0;
    public $equityPercentage                 = 0;
    public $liquidationValue                 = 0;
    public $longMarginValue                  = 0;
    public $longOptionMarketValue            = 0;
    public $longStockValue                   = 0;
    public $maintenanceCall                  = 0;
    public $maintenanceRequirement           = 0;
    public $margin                           = 0;
    public $marginEquity                     = 0;
    public $moneyMarketFund                  = 0;
    public $mutualFundValue                  = 0;
    public $regTCall                         = 0;
    public $shortMarginValue                 = 0;
    public $shortOptionMarketValue           = 0;
    public $shortStockValue                  = 0;
    public $totalCash                        = 0;
    public $isInCall                         = 0;
    public $pendingDeposits                  = 0;
    public $marginBalance                    = 0;
    public $shortBalance                     = 0;
    public $accountValue                     = 0;

    public function __construct( array $values ) {
        foreach ( $values as $name => $value ):
            $this->{$name} = (float)$value;
        endforeach;
    }
}