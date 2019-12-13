<?php

namespace MichaelDrennen\TDAmeritrade\Tests;

use Carbon\Carbon;
use MichaelDrennen\TDAmeritradeAPI\Authenticator;
use MichaelDrennen\TDAmeritradeAPI\Responses\Qutoes\Quote;
use MichaelDrennen\TDAmeritradeAPI\Responses\SecuritiesAccount;
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

        $authenticator   = new Authenticator( $oauthConsumerKey,
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
        $tdAmeritradeApi = $authenticator->authenticate();

        return $tdAmeritradeApi;
    }

    /**
     * @test
     * @group poop
     */
    public function constructorShouldCreateInstance() {

        $accountId    = getenv( 'TDAMERITRADE_ACCOUNT_ID' );
        $tdAmeritrade = $this->getTDAmeritradeAPIInstance();
        $this->assertInstanceOf( TDAmeritradeAPI::class, $tdAmeritrade );
        $code = $tdAmeritrade->getToken();
        $this->assertNotEmpty( $code );

//        $securitiesAccounts = $tdAmeritrade->getAccounts();
//        $this->assertInstanceOf( SecuritiesAccounts::class, $securitiesAccounts );
//
        $securitiesAccount = $tdAmeritrade->getAccount( $accountId );
        $this->assertInstanceOf( SecuritiesAccount::class, $securitiesAccount );

        //print_r( $securitiesAccount );


        //$tdAmeritrade->buyStockMarketPrice( $accountId, 'LODE', 1 );
//        $tdAmeritrade->sellStockMarketPrice( $accountId, 'LODE', 1 );
//        $tdAmeritrade->sellStockAllSharesMarketPrice( $accountId, 'LODE' );
        //$tdAmeritrade->createSavedBuyMarketOrder( $accountId, 'LODE', 1 );

//        $quote = $tdAmeritrade->getStockQuote( 'WGP' );
//        print_r( $quote );
//        $this->assertInstanceOf( Quote::class, $quote );
//
//
//        //$date        = Carbon::create( 2019, 11, 14, 12, 0, 0, 'America/New_York' );
//        $date        = Carbon::now( 'America/New_York' )->addMonth()->setHour(20);
//        $marketHours = $tdAmeritrade->getEquityMarketHours( $date );
//        print_r( $marketHours );
    }

}
