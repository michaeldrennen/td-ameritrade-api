<?php

namespace MichaelDrennen\TDAmeritrade\Tests;

use MichaelDrennen\TDAmeritradeAPI\TDAmeritradeAPI;
use PHPUnit\Framework\TestCase;

class TDAmeritradeTest extends TestCase {


    /**
     * @test
     */
    public function constructorShouldCreateInstance() {
        $tdAmeritrade = new TDAmeritradeAPI();
        var_dump( $tdAmeritrade );

        $this->assertInstanceOf( TDAmeritradeAPI::class, $tdAmeritrade );
    }

}
