<?php

namespace MichaelDrennen\TDAmeritrade\Tests;

use MichaelDrennen\TDAmeritradeAPI\TDAmeritradeAPI;
use PHPUnit\Framework\TestCase;

class TDAmeritradeTest extends TestCase {


    /**
     * @return TDAmeritradeAPI
     */
    protected function getTDAmeritradeAPIInstance(): TDAmeritradeAPI {
        $callbackUrl      = getenv( 'TDAMERITRADE_CALLBACK_URL' );
        $oauthConsumerKey = getenv( 'TDAMERITRADE_OAUTH_CONSUMER_KEY' );
        $userName         = getenv( 'TDAMERITRADE_USERNAME' );
        $password         = getenv( 'TDAMERITRADE_PASSWORD' );
        $question_1       = getenv( 'TDAMERITRADE_QUESTION_1' );
        $answer_1         = getenv( 'TDAMERITRADE_ANSWER_1' );
        $question_2       = getenv( 'TDAMERITRADE_QUESTION_2' );
        $answer_2         = getenv( 'TDAMERITRADE_ANSWER_2' );
        $question_3       = getenv( 'TDAMERITRADE_QUESTION_3' );
        $answer_3         = getenv( 'TDAMERITRADE_ANSWER_3' );
        $question_4       = getenv( 'TDAMERITRADE_QUESTION_4' );
        $answer_4         = getenv( 'TDAMERITRADE_ANSWER_4' );

        return new TDAmeritradeAPI( $oauthConsumerKey,
                                    $userName,
                                    $password,
                                    $callbackUrl,
                                    $question_1,
                                    $answer_1,
                                    $question_2,
                                    $answer_2,
                                    $question_3,
                                    $answer_3,
                                    $question_4,
                                    $answer_4 );
    }

    /**
     * @test
     */
    public function constructorShouldCreateInstance() {
        $tdAmeritrade = $this->getTDAmeritradeAPIInstance();
        $this->assertInstanceOf( TDAmeritradeAPI::class, $tdAmeritrade );
        $code = $tdAmeritrade->getCode();
        $this->assertNotEmpty( $code );

    }

}
