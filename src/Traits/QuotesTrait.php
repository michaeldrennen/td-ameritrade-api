<?php

namespace MichaelDrennen\TDAmeritradeAPI\Traits;

use Exception;
use MichaelDrennen\TDAmeritradeAPI\Responses\Qutoes\Quote;

trait QuotesTrait {
    use BaseTrait;

    /**
     * @param string $ticker
     * @return Quote
     * @throws Exception
     */
    public function getStockQuote( string $ticker ): Quote {
        $uri      = 'v1/marketdata/' . $ticker . '/quotes';
        $options  = [];
        $response = $this->guzzle->request( 'GET', $uri, $options );
        $body     = $response->getBody()->getContents();

        $json = json_decode( $body, TRUE );

        if ( FALSE === isset( $json[ $ticker ] ) ):
            throw new Exception( "No results returned for ticker " . $ticker );
        endif;

        return new Quote( $json[ $ticker ] );
    }

}