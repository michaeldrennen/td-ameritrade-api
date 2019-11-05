<?php

namespace MichaelDrennen\TDAmeritrade\Tests;

use MichaelDrennen\TDAmeritradeAPI\TDAmeritradeAPI;
use PHPUnit\Framework\TestCase;

class AbstractTestCase extends TestCase {


    protected function getTDAmeritradeAPIInstance(){
        $oauthConsumerKey = getenv('asdf');
    }

    /**
     * @test
     */
    public function constructorShouldCreateInstance() {
        $tdAmeritrade = new TDAmeritradeAPI();
        var_dump( $tdAmeritrade );

        $this->assertInstanceOf( TDAmeritradeAPI::class, $tdAmeritrade );
    }

}
