<?php

namespace MichaelDrennen\TDAmeritradeAPI\Responses;


use Carbon\Carbon;

class MarketHours {
    /**
     * @var string
     */
    public $category;

    /**
     * @var string
     */
    public $date;

    /**
     * @var string
     */
    public $exchange;


    /**
     * @var boolean
     */
    public $isOpen;

    /**
     * @var string
     */
    public $marketType; // Ex: 'BOND' or 'EQUITY' or 'ETF' or 'FOREX' or 'FUTURE' or 'FUTURE_OPTION' or 'INDEX' or 'INDICATOR' or 'MUTUAL_FUND' or 'OPTION' or 'UNKNOWN'

    /**
     * @var string
     */
    public $product;


    /**
     * @var string
     */
    public $productName;

    /**
     * @var object
     */
    public $sessionHours;


    public function __construct( array $values ) {
        $this->category    = $values[ 'category' ];
        $this->date        = $values[ 'date' ];
        $this->exchange    = $values[ 'exchange' ];
        $this->isOpen      = (bool)$values[ 'isOpen' ];
        $this->marketType  = $values[ 'marketType' ];
        $this->product     = $values[ 'product' ];
        $this->productName = $values[ 'productName' ];

        $this->setSessionHours( $values[ 'sessionHours' ] );

    }

    protected function setSessionHours( array $sessionHours = [] ) {
        if ( empty( $sessionHours ) ):
            $this->sessionHours = NULL;
        endif;

        $this->sessionHours = [];
        if ( isset( $sessionHours[ 'preMarket' ][ 0 ] ) ):
            $this->sessionHours[ 'preMarket' ][ 'start' ] = Carbon::parse( $sessionHours[ 'preMarket' ][ 0 ][ 'start' ] );
            $this->sessionHours[ 'preMarket' ][ 'end' ]   = Carbon::parse( $sessionHours[ 'preMarket' ][ 0 ][ 'start' ] );
        endif;

        if ( isset( $sessionHours[ 'regularMarket' ][ 0 ] ) ):
            $this->sessionHours[ 'regularMarket' ][ 'start' ] = Carbon::parse( $sessionHours[ 'regularMarket' ][ 0 ][ 'start' ] );
            $this->sessionHours[ 'regularMarket' ][ 'end' ]   = Carbon::parse( $sessionHours[ 'regularMarket' ][ 0 ][ 'start' ] );
        endif;

        if ( isset( $sessionHours[ 'postMarket' ][ 0 ] ) ):
            $this->sessionHours[ 'postMarket' ][ 'start' ] = Carbon::parse( $sessionHours[ 'postMarket' ][ 0 ][ 'start' ] );
            $this->sessionHours[ 'postMarket' ][ 'end' ]   = Carbon::parse( $sessionHours[ 'postMarket' ][ 0 ][ 'start' ] );
        endif;
    }

}