<?php

namespace MichaelDrennen\TDAmeritrade\Tests;

use MichaelDrennen\TDAmeritradeAPI\TDAmeritradeAPI;
use PHPUnit\Framework\TestCase;

class TDAmeritradeTest extends TestCase {


    /**
     * @return TDAmeritradeAPI
     */
    protected function getTDAmeritradeAPIInstance(): TDAmeritradeAPI {
        $oauthConsumerKey = getenv( 'TDAMERITRADE_OAUTH_CONSUMER_KEY' );
        $userName         = getenv( 'TDAMERITRADE_USERNAME' );
        $password         = getenv( 'TDAMERITRADE_PASSWORD' );
        $callbackUrl      = getenv( 'TDAMERITRADE_CALLBACK_URL' );
        return new TDAmeritradeAPI( $oauthConsumerKey, $userName, $password, $callbackUrl );
    }

    /**
     * @test
     */
    public function constructorShouldCreateInstance() {
        $tdAmeritrade = $this->getTDAmeritradeAPIInstance();
        $tdAmeritrade->authenticate();

        $this->assertInstanceOf( TDAmeritradeAPI::class, $tdAmeritrade );
    }

}
