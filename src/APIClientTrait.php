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
     * @return Client
     */
    protected function createGuzzleClient( string $token = NULL ): Client {
        $headers             = [];
        $headers[ 'Accept' ] = 'application/json';

        // The $token param will not be sent on the first API call which should exchange the request code for a token.
        if ( $token ):
            $headers[ 'Authorization' ] = 'Bearer ' . $token;
        endif;

        $options = [
            'base_uri' => $this->baseURI,
            'headers'  => $headers,
            'debug'    => TRUE,
        ];
        return new Client( $options );
    }
}