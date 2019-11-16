<?php

namespace MichaelDrennen\TDAmeritradeAPI;


use GuzzleHttp\Client;

trait APIClientTrait {

    private $baseURI = 'https://api.tdameritrade.com/';

    /**
     * @var Client
     */
    protected $guzzle;


    /**
     * @param string|NULL $token
     * @param bool $debug
     * @return Client
     */
    protected function createGuzzleClient( string $token = NULL, bool $debug = FALSE ): Client {
        $headers             = [];
        $headers[ 'Accept' ] = 'application/json';

        // The $token param will not be sent on the first API call which should exchange the request code for a token.
        if ( $token ):
            $headers[ 'Authorization' ] = 'Bearer ' . $token;
        endif;

        $options = [
            'base_uri' => $this->baseURI,
            'headers'  => $headers,
            'debug'    => $debug,
        ];
        return new Client( $options );
    }
}