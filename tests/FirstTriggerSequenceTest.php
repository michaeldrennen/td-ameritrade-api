<?php

namespace MichaelDrennen\TDAmeritrade\Tests;

use Carbon\Carbon;
use MichaelDrennen\TDAmeritradeAPI\Responses\AccountsAndTrading\SecuritiesAccount;
use MichaelDrennen\TDAmeritradeAPI\Responses\AccountsAndTrading\SecuritiesAccounts;
use MichaelDrennen\TDAmeritradeAPI\Responses\MarketHours\MarketHours;
use MichaelDrennen\TDAmeritradeAPI\Responses\Quotes\Quote;
use MichaelDrennen\TDAmeritradeAPI\TDAmeritradeAPI;

class FirstTriggerSequenceTest extends AbstractParentTest {

    /**
     * @test
     * @group fts
     */
    public function firstTriggerSequenceShouldSubmitTwoTrades() {

        $accountId    = getenv( 'TDAMERITRADE_ACCOUNT_ID' );
        $tdAmeritrade = $this->getTDAmeritradeAPIInstance();
        $refreshToken = $tdAmeritrade->getRefreshToken();
        $tdAmeritrade = $this->getTDAmeritradeAPIInstance( $refreshToken );
        $securitiesAccount = $tdAmeritrade->getAccount( $accountId );

        print_r($securitiesAccount);


        //$tdAmeritrade->buyStockMarketPrice( $accountId, 'LODE', 1 );
//        $tdAmeritrade->sellStockMarketPrice( $accountId, 'LODE', 1 );
//        $tdAmeritrade->sellStockAllSharesMarketPrice( $accountId, 'LODE' );
        //$tdAmeritrade->createSavedBuyMarketOrder( $accountId, 'LODE', 1 );

//        $quote = $tdAmeritrade->getStockQuote( 'U' );
        //print_r( $quote );
//        $this->assertInstanceOf( Quote::class, $quote );
//
//
//        //$date        = Carbon::create( 2019, 11, 14, 12, 0, 0, 'America/New_York' );
//        $date        = Carbon::now( 'America/New_York' )->addMonth()->setHour( 20 );
//        $marketHours = $tdAmeritrade->getEquityMarketHours( $date );
        //print_r( $marketHours );
//        $this->assertInstanceOf( MarketHours::class, $marketHours );


        //$result = $tdAmeritrade->sellStockSharesLimitPrice( $accountId, 'U', 1, 400.25 );
        //var_dump( $result );

//        $result = $tdAmeritrade->sellStockAllSharesLimitPrice( $accountId, 'U', 400.25 );
//        var_dump( $result );

    }

}
