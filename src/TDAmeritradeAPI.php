<?php

namespace MichaelDrennen\TDAmeritradeAPI;

use GuzzleHttp\Client;

class TDAmeritradeAPI {

    const BASE_URI = 'https://auth.tdameritrade.com';

    protected $code;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    public function __construct( string $oauthConsumerKey,
                                 string $userName,
                                 string $password,
                                 string $callbackUrl,
                                 string $question1,
                                 string $answer1,
                                 string $question2,
                                 string $answer2,
                                 string $question3,
                                 string $answer3,
                                 string $question4,
                                 string $answer4 ) {

        $authenticator = new Authenticator( $oauthConsumerKey,
                                            $userName,
                                            $password,
                                            $callbackUrl,
                                            $question1,
                                            $answer1,
                                            $question2,
                                            $answer2,
                                            $question3,
                                            $answer3,
                                            $question4,
                                            $answer4 );

        $this->code = $authenticator->authenticate();

        $this->guzzle = $this->createGuzzleClient();

    }

    /**
     * A simple accessor method to get the authentication code from TD Ameritrade.
     * @return string
     */
    public function getCode(): string {
        return $this->code;
    }

    /**
     * @param string|NULL $token
     * @return \GuzzleHttp\Client
     */
    protected function createGuzzleClient(): Client {
        $headers                    = [];
        $headers[ 'Accept' ]        = 'application/json';
        $headers[ 'Authorization' ] = 'Bearer ' . $this->code;
        $options                    = [
            'base_uri' => self::BASE_URI,
            'headers'  => $headers,
        ];
        return new Client( $options );
    }

}