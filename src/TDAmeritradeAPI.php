<?php

namespace MichaelDrennen\TDAmeritradeAPI;


use MichaelDrennen\TDAmeritradeAPI\Exceptions\BaseClientException;
use MichaelDrennen\TDAmeritradeAPI\Exceptions\BaseServerException;
use MichaelDrennen\TDAmeritradeAPI\Exceptions\ClientExceptionFactory;
use MichaelDrennen\TDAmeritradeAPI\Responses\SecuritiesAccount;
use MichaelDrennen\TDAmeritradeAPI\Responses\SecuritiesAccounts;
use GuzzleHttp\RequestOptions;
use MichaelDrennen\TDAmeritradeAPI\Traits\MarketHoursTrait;
use MichaelDrennen\TDAmeritradeAPI\Traits\QuotesTrait;

class TDAmeritradeAPI {

    use APIClientTrait;
    use QuotesTrait;
    use MarketHoursTrait;

    protected $userName;
    protected $token;

    public function __construct( string $userName = NULL, string $token = NULL, bool $debug = FALSE ) {
        $this->userName = $userName;
        $this->token    = $token;
        $this->guzzle   = $this->createGuzzleClient( $this->token, $debug );

    }


//    public function login( string $oauthConsumerKey,
//                           string $userName,
//                           string $password,
//                           string $callbackUrl,
//                           string $question1,
//                           string $answer1,
//                           string $question2,
//                           string $answer2,
//                           string $question3,
//                           string $answer3,
//                           string $question4,
//                           string $answer4,
//                           bool $debug = FALSE ) {
//        $this->userName = $userName;
//
//        $authenticator = new Authenticator( $oauthConsumerKey,
//                                            $userName,
//                                            $password,
//                                            $callbackUrl,
//                                            $question1,
//                                            $answer1,
//                                            $question2,
//                                            $answer2,
//                                            $question3,
//                                            $answer3,
//                                            $question4,
//                                            $answer4 );
//
//        $this->token = $authenticator->authenticate();
//
//        $this->guzzle = $this->createGuzzleClient( $this->token, $debug );
//
//    }

    public function getUserName(): string {
        return $this->userName;
    }

    /**
     * A simple accessor method to return the authentication token from TD Ameritrade.
     * @return string
     */
    public function getToken(): string {
        return $this->token;
    }


    /**
     * @return SecuritiesAccounts
     * @throws \GuzzleHttp\Exception\GuzzleException
     * https://api.tdameritrade.com/v1/accounts
     */
    public function getAccounts(): SecuritiesAccounts {
        $uri      = 'v1/accounts';
        $options  = [
            'query' => [
                'fields' => 'positions,orders'
            ]
        ];
        $response = $this->guzzle->request( 'GET', $uri, $options );
        $body     = $response->getBody();
        $json     = json_decode( $body, TRUE );

        return new SecuritiesAccounts( $json );
    }


    /**
     * @param string $accountId
     * @return SecuritiesAccount
     * @throws \GuzzleHttp\Exception\GuzzleException
     * https://api.tdameritrade.com/v1/accounts
     */
    public function getAccount( string $accountId ): SecuritiesAccount {
        $uri      = 'v1/accounts/' . $accountId;
        $options  = [
            'query' => [
                'fields' => 'positions,orders'
            ]
        ];
        $response = $this->guzzle->request( 'GET', $uri, $options );
        $body     = $response->getBody();
        $json     = json_decode( $body, TRUE );

        return new SecuritiesAccount( $json[ 'securitiesAccount' ] );
    }


    /**
     * TODO build out and test.
     * @param string $accountId
     * @param string $ticker
     * @param int $quantity
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function createSavedBuyMarketOrder( string $accountId, string $ticker, int $quantity ) {
        $uri        = 'v1/accounts/' . $accountId . '/savedorders';
        $orderArray = [
            'orderType'          => 'MARKET',
            'session'            => 'NORMAL',
            'duration'           => 'DAY',
            'orderStrategyType'  => 'SINGLE',
            'orderLegCollection' => [ [
                                          'orderLegType' => 'EQUITY',
                                          'instruction'  => 'BUY',
                                          'quantity'     => $quantity,
                                          'quantityType' => 'SHARES',
                                          'instrument'   => [
                                              'symbol'    => $ticker,
                                              'assetType' => 'EQUITY',
                                          ] ],
            ],
        ];

        try {
            $this->guzzle->request( 'POST', $uri, [ RequestOptions::JSON => $orderArray ] );
            return TRUE;
        } catch ( \Exception $exception ) {
            throw $exception;
        }
    }


    /**
     * @param string $accountId
     * @param string $ticker
     * @param int $quantity
     * @param string $quantityType Ex: SHARES or DOLLARS or ALL_SHARES
     * @param string $orderType Ex: MARKET
     * @param string $instruction Ex: BUY
     * @return bool
     * @throws BaseClientException
     * @throws BaseServerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @see https://developer.tdameritrade.com/account-access/apis/post/accounts/%7BaccountId%7D/orders-0
     * @see https://developer.tdameritrade.com/content/place-order-samples
     * @see http://docs.guzzlephp.org/en/latest/request-options.html#json
     */
    public function placeOrder( string $accountId, string $ticker, int $quantity, string $quantityType, string $orderType, string $instruction ): bool {
        $uri        = 'v1/accounts/' . $accountId . '/orders';
        $orderArray = [
            'orderType'          => $orderType,
            'session'            => 'NORMAL',
            'duration'           => 'DAY',
            'orderStrategyType'  => 'SINGLE',
            'orderLegCollection' => [ [
                                          'orderLegType' => 'EQUITY',
                                          'instruction'  => $instruction,
                                          'quantity'     => $quantity,
                                          'quantityType' => 'SHARES',
                                          'instrument'   => [
                                              'symbol'    => $ticker,
                                              'assetType' => 'EQUITY',
                                          ] ],
            ],
        ];

        try {
            $this->guzzle->request( 'POST', $uri, [ RequestOptions::JSON => $orderArray ] );
            return TRUE;
        } catch ( \GuzzleHttp\Exception\ClientException $exception ) {
            throw ClientExceptionFactory::create( $exception, [
                'ticker'   => $ticker,
                'quantity' => $quantity,
            ] );
        } catch ( \GuzzleHttp\Exception\ServerException $exception ) {
            throw new BaseServerException( $exception->getMessage(), $exception->getCode(), $exception, [
                'ticker'   => $ticker,
                'quantity' => $quantity,
            ] );
        } catch ( \Exception $exception ) {
            throw $exception;
        }
    }


    /**
     * @param string $accountId
     * @param string $ticker
     * @param int $quantity
     * @return boolean
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function buyStockSharesMarketPrice( string $accountId, string $ticker, int $quantity ): bool {
        return $this->placeOrder( $accountId,
                                  $ticker,
                                  $quantity,
                                  'SHARES',
                                  'MARKET',
                                  'BUY' );
    }


    /**
     * @param string $accountId
     * @param string $ticker
     * @param int $quantity
     * @return bool
     * @throws BaseClientException
     * @throws BaseServerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sellStockSharesMarketPrice( string $accountId, string $ticker, int $quantity ): bool {
        return $this->placeOrder( $accountId,
                                  $ticker,
                                  $quantity,
                                  'SHARES',
                                  'MARKET',
                                  'SELL' );
    }


    /**
     * TODO Waiting to hear back from their tech support.
     * @param string $accountId
     * @param string $ticker
     * @return bool
     * @throws BaseClientException
     * @throws BaseServerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sellStockAllSharesMarketPrice( string $accountId, string $ticker ): bool {
        $uri        = 'v1/accounts/' . $accountId . '/orders';
        $orderArray = [
            'orderType'          => 'MARKET',
            'session'            => 'NORMAL',
            'duration'           => 'DAY',
            'orderStrategyType'  => 'SINGLE',
            'orderLegCollection' => [ [
                                          'orderLegType' => 'EQUITY',
                                          'instruction'  => 'SELL',
                                          //                                          'quantity'     => 100000000,
                                          'quantityType' => 'ALL_SHARES',
                                          'instrument'   => [
                                              'symbol'    => $ticker,
                                              'assetType' => 'EQUITY',
                                          ] ],
            ],
        ];

        try {
            $this->guzzle->request( 'POST', $uri, [ RequestOptions::JSON => $orderArray ] );
            return TRUE;
        } catch ( \GuzzleHttp\Exception\ClientException $exception ) {
            throw ClientExceptionFactory::create( $exception, [
                'ticker'   => $ticker,
                'quantity' => 'ALL SHARES',
            ] );
        } catch ( \GuzzleHttp\Exception\ServerException $exception ) {
            throw new BaseServerException( $exception->getMessage(), $exception->getCode(), $exception, [
                'ticker'   => $ticker,
                'quantity' => 'ALL SHARES',
            ] );
        } catch ( \Exception $exception ) {
            throw $exception;
        }
    }

}