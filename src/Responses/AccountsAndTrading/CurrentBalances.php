<?php

namespace MichaelDrennen\TDAmeritradeAPI\Responses\AccountsAndTrading;

/**
 * Class CurrentBalance
 * @package MichaelDrennen\TDAmeritradeAPI\Responses
 */
class CurrentBalances {
    public $accruedInterest                  = 0;
    public $cashBalance                      = 0;
    public $cashReceipts                     = 0;
    public $longOptionMarketValue            = 0;
    public $liquidationValue                 = 0;
    public $longMarketValue                  = 0;
    public $moneyMarketFund                  = 0;
    public $savings                          = 0;
    public $shortMarketValue                 = 0;
    public $pendingDeposits                  = 0;
    public $availableFunds                   = 0;
    public $availableFundsNonMarginableTrade = 0;
    public $buyingPower                      = 0;
    public $buyingPowerNonMarginableTrade    = 0;
    public $dayTradingBuyingPower            = 0;
    public $equity                           = 0;
    public $equityPercentage                 = 0;
    public $longMarginValue                  = 0;
    public $maintenanceCall                  = 0;
    public $maintenanceRequirement           = 0;
    public $marginBalance                    = 0;
    public $regTCall                         = 0;
    public $shortBalance                     = 0;
    public $shortMarginValue                 = 0;
    public $shortOptionMarketValue           = 0;
    public $sma                              = 0;
    public $bondValue                        = 0;
    public $cashAvailableForTrading          = 0;
    public $cashAvailableForWithdrawal       = 0;
    public $cashCall                         = 0;
    public $cashDebitCallValue               = 0;
    public $longNonMarginableMarketValue     = 0;
    public $totalCash                        = 0;
    public $unsettledCash                    = 0;


    public function __construct( array $values ) {
        foreach ( $values as $name => $value ):
            $this->{$name} = (float)$value;
        endforeach;
    }

}