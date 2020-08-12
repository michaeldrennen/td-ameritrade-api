<?php

namespace MichaelDrennen\TDAmeritradeAPI\Responses\Quotes;


class Quote {
    public $assetType;
    public $symbol;
    public $description;
    public $bidPrice;
    public $bidSize;
    public $bidId;
    public $askPrice;
    public $askSize;
    public $askId;
    public $lastPrice;
    public $lastSize;
    public $lastId;
    public $openPrice;
    public $highPrice;
    public $lowPrice;
    public $bidTick;
    public $closePrice;
    public $netChange;
    public $totalVolume;
    public $quoteTimeInLong;
    public $tradeTimeInLong;
    public $mark;
    public $exchange;
    public $exchangeName;
    public $marginable;
    public $shortable;
    public $volatility;
    public $digits;
    public $_52WkHigh;
    public $_52WkLow;
    public $nAV;
    public $peRatio;
    public $divAmount;
    public $divYield;
    public $divDate;
    public $securityStatus;
    public $regularMarketLastPrice;
    public $regularMarketLastSize;
    public $regularMarketNetChange;
    public $regularMarketTradeTimeInLong;
    public $netPercentChangeInDouble;
    public $markChangeInDouble;
    public $markPercentChangeInDouble;
    public $regularMarketPercentChangeInDouble;
    public $delayed;

    public function __construct( array $values ) {
        $this->assetType                          = $values[ 'assetType' ]; // Ex: EQUITY
        $this->symbol                             = $values[ 'symbol' ]; // LODE
        $this->description                        = $values[ 'description' ]; // Comstock Mining, Inc. Common Stock
        $this->bidPrice                           = (float)$values[ 'bidPrice' ]; // 0.087
        $this->bidSize                            = $values[ 'bidSize' ]; // 100000
        $this->bidId                              = $values[ 'bidId' ]; // K
        $this->askPrice                           = (float)$values[ 'askPrice' ]; // 0.0895
        $this->askSize                            = $values[ 'askSize' ]; // 400
        $this->askId                              = $values[ 'askId' ]; // P
        $this->lastPrice                          = (float)$values[ 'lastPrice' ];
        $this->lastSize                           = $values[ 'lastSize' ];
        $this->lastId                             = $values[ 'lastId' ];
        $this->openPrice                          = $values[ 'openPrice' ];
        $this->highPrice                          = $values[ 'highPrice' ];
        $this->lowPrice                           = $values[ 'lowPrice' ];
        $this->bidTick                            = $values[ 'bidTick' ];
        $this->closePrice                         = (float)$values[ 'closePrice' ];
        $this->netChange                          = $values[ 'netChange' ];
        $this->totalVolume                        = $values[ 'totalVolume' ];
        $this->quoteTimeInLong                    = $values[ 'quoteTimeInLong' ];
        $this->tradeTimeInLong                    = $values[ 'tradeTimeInLong' ];
        $this->mark                               = (float)$values[ 'mark' ];
        $this->exchange                           = $values[ 'exchange' ];
        $this->exchangeName                       = $values[ 'exchangeName' ];
        $this->marginable                         = $values[ 'marginable' ];
        $this->shortable                          = $values[ 'shortable' ];
        $this->volatility                         = $values[ 'volatility' ];
        $this->digits                             = $values[ 'digits' ];
        $this->_52WkHigh                          = (float)$values[ '52WkHigh' ];
        $this->_52WkLow                           = (float)$values[ '52WkLow' ];
        $this->nAV                                = $values[ 'nAV' ];
        $this->peRatio                            = $values[ 'peRatio' ];
        $this->divAmount                          = $values[ 'divAmount' ];
        $this->divYield                           = $values[ 'divYield' ];
        $this->divDate                            = $values[ 'divDate' ];
        $this->securityStatus                     = $values[ 'securityStatus' ];
        $this->regularMarketLastPrice             = (float)$values[ 'regularMarketLastPrice' ];
        $this->regularMarketLastSize              = $values[ 'regularMarketLastSize' ];
        $this->regularMarketNetChange             = $values[ 'regularMarketNetChange' ];
        $this->regularMarketTradeTimeInLong       = $values[ 'regularMarketTradeTimeInLong' ];
        $this->netPercentChangeInDouble           = $values[ 'netPercentChangeInDouble' ];
        $this->markChangeInDouble                 = $values[ 'markChangeInDouble' ];
        $this->markPercentChangeInDouble          = $values[ 'markPercentChangeInDouble' ];
        $this->regularMarketPercentChangeInDouble = $values[ 'regularMarketPercentChangeInDouble' ];
        $this->delayed                            = $values[ 'delayed' ];
    }
}