<?php

namespace MichaelDrennen\TDAmeritradeAPI\Responses\MarketHours;


use Carbon\Carbon;
use Exception;

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

    protected function setSessionHours( array $sessionHours = NULL ) {
        if ( empty( $sessionHours ) ):
            $this->sessionHours = NULL;
        endif;

        $this->sessionHours = [];
        if ( isset( $sessionHours[ 'preMarket' ][ 0 ] ) ):
            $this->sessionHours[ 'preMarket' ][ 'start' ] = Carbon::parse( $sessionHours[ 'preMarket' ][ 0 ][ 'start' ] );
            $this->sessionHours[ 'preMarket' ][ 'end' ]   = Carbon::parse( $sessionHours[ 'preMarket' ][ 0 ][ 'end' ] );
        endif;

        if ( isset( $sessionHours[ 'regularMarket' ][ 0 ] ) ):
            $this->sessionHours[ 'regularMarket' ][ 'start' ] = Carbon::parse( $sessionHours[ 'regularMarket' ][ 0 ][ 'start' ] );
            $this->sessionHours[ 'regularMarket' ][ 'end' ]   = Carbon::parse( $sessionHours[ 'regularMarket' ][ 0 ][ 'end' ] );
        endif;

        if ( isset( $sessionHours[ 'postMarket' ][ 0 ] ) ):
            $this->sessionHours[ 'postMarket' ][ 'start' ] = Carbon::parse( $sessionHours[ 'postMarket' ][ 0 ][ 'start' ] );
            $this->sessionHours[ 'postMarket' ][ 'end' ]   = Carbon::parse( $sessionHours[ 'postMarket' ][ 0 ][ 'end' ] );
        endif;
    }

    public function isTradingDay(): bool {
        return $this->isOpen;
    }


    /**
     * @return bool
     * @throws Exception
     */
    public function marketIsOpen(): bool {
        if ( FALSE == $this->isOpen ):
            return FALSE;
        endif;

        $now = Carbon::now( "America/New_York" );

        $nowUnix = $now->copy()->timestamp;

        if ( !isset( $this->sessionHours[ 'regularMarket' ][ 'start' ] ) ):
            throw new \Exception( "Unable to determine if market is open because regular market start time not set." );
        endif;

        if ( !isset( $this->sessionHours[ 'regularMarket' ][ 'end' ] ) ):
            throw new \Exception( "Unable to determine if market is open because regular market end time not set." );
        endif;


        $openUnix  = $this->sessionHours[ 'regularMarket' ][ 'start' ]->copy()->timestamp;
        $closeUnix = $this->sessionHours[ 'regularMarket' ][ 'end' ]->copy()->timestamp;

        if ( $nowUnix < $openUnix ):
            return FALSE;
        endif;

        if ( $nowUnix > $closeUnix ):
            return FALSE;
        endif;

        return TRUE;
    }


    /**
     * @return Carbon
     * @throws Exception
     */
    public function regularMarketOpen(): Carbon {
        if( isset($this->sessionHours['regularMarket']['start']) ):
            return $this->sessionHours['regularMarket']['start'];
        endif;
        throw new Exception("The regular market open time was not set.");
    }


    /**
     * @return Carbon
     * @throws Exception
     */
    public function regularMarketClose(): Carbon {
        if( isset($this->sessionHours['regularMarket']['end']) ):
            return $this->sessionHours['regularMarket']['end'];
        endif;
        throw new Exception("The regular market end time was not set.");
    }

}