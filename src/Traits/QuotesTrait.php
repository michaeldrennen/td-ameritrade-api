<?php

namespace MichaelDrennen\TDAmeritradeAPI\Traits;


use MichaelDrennen\TDAmeritradeAPI\Responses\Qutoes\Quote;

trait QuotesTrait {
    use BaseTrait;

    public function getStockQuote( string $ticker ): Quote {
        $uri      = 'v1/marketdata/' . $ticker . '/quotes';
        $options  = [];
        $response = $this->guzzle->request( 'GET', $uri, $options );
        $body     = $response->getBody();
        $json     = json_decode( $body, TRUE );

        return new Quote( $json[ $ticker ] );
    }

}