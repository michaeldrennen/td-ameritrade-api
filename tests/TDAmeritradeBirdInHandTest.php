<?php

namespace MichaelDrennen\TDAmeritrade\Tests;

use Carbon\Carbon;
use MichaelDrennen\TDAmeritradeAPI\Responses\AccountsAndTrading\Position;
use MichaelDrennen\TDAmeritradeAPI\Responses\AccountsAndTrading\SecuritiesAccount;
use MichaelDrennen\TDAmeritradeAPI\Responses\AccountsAndTrading\SecuritiesAccounts;
use MichaelDrennen\TDAmeritradeAPI\Responses\MarketHours\MarketHours;
use MichaelDrennen\TDAmeritradeAPI\Responses\Quotes\Quote;
use MichaelDrennen\TDAmeritradeAPI\TDAmeritradeAPI;

class TDAmeritradeBirdInHandTest extends AbstractParentTest {

    /**
     * @test
     * @group bird
     */
    public function marketBuyShouldTriggerLimitSell() {

        $ticker = 'LODE';

        $accountId         = getenv( 'TDAMERITRADE_ACCOUNT_ID' );
        $tdAmeritrade      = $this->getTDAmeritradeAPIInstance();
        $accessToken       = $tdAmeritrade->getAccessToken();
        $refreshToken      = $tdAmeritrade->getRefreshToken();
        $tdAmeritrade      = $this->getTDAmeritradeAPIInstance( $refreshToken );
        $securitiesAccount = $tdAmeritrade->getAccount( $accountId );

//        $quote = $tdAmeritrade->getStockQuote( $ticker );
//        print_r( $quote );
//
//        $quantity  = 1;
//        $exitPrice = NULL;
//        $orders    = $tdAmeritrade->buyStockSharesMarketPrice( $accountId, $ticker, $quantity );
//        var_dump( $orders );

        // Get ORDER STATUS
//        $maxResults      = NULL;
//        $fromEnteredTime = Carbon::create( 2021, 2, 1 )->toDateString();
//        $toEnteredTime   = Carbon::create( 2021, 2, 8 )->toDateString();
//        $orders          = $tdAmeritrade->getOrdersByQuery( $accountId,
//                                                            $maxResults,
//                                                            $fromEnteredTime,
//                                                            $toEnteredTime );
//        print_r( $orders );
//        print_r( count($orders->orders) );


//        $account = $tdAmeritrade->getAccount($accountId);
//        print_r($account->positions);


//        /**
//         * @var Position $positon
//         */
//        foreach($account->positions as $positon):
//            var_dump($positon->averagePrice);
//            var_dump($positon->longQuantity);
//            var_dump($positon->instrument['symbol']);
//        endforeach;


// Take the bird in hand
//        $minPercentProfit = 1.10;
//        $orders           = $tdAmeritrade->placeSellLimitOrdersOverPercentProfitOnAllPositions( $accountId,
//                                                                                                $minPercentProfit );
//
//        print_r($orders);


    }

}
