<?php

namespace MichaelDrennen\TDAmeritradeAPI;

use GuzzleHttp\RequestOptions;

use MichaelDrennen\TDAmeritradeAPI\Exceptions\BaseClientException;
use MichaelDrennen\TDAmeritradeAPI\Exceptions\BaseServerException;
use MichaelDrennen\TDAmeritradeAPI\Exceptions\ClientExceptionFactory;
use MichaelDrennen\TDAmeritradeAPI\Responses\AccountsAndTrading\Order;
use MichaelDrennen\TDAmeritradeAPI\Responses\AccountsAndTrading\Orders;
use MichaelDrennen\TDAmeritradeAPI\Responses\AccountsAndTrading\Position;
use MichaelDrennen\TDAmeritradeAPI\Responses\AccountsAndTrading\SecuritiesAccount;
use MichaelDrennen\TDAmeritradeAPI\Responses\AccountsAndTrading\SecuritiesAccounts;

use MichaelDrennen\TDAmeritradeAPI\Traits\AuthenticationTrait;
use MichaelDrennen\TDAmeritradeAPI\Traits\MarketHoursTrait;
use MichaelDrennen\TDAmeritradeAPI\Traits\QuotesTrait;

class TDAmeritradeAPI {

    use APIClientTrait;
    use QuotesTrait;
    use MarketHoursTrait;
    use AuthenticationTrait;

    protected $userName;
    protected $accessToken;
    protected $refreshToken;
    protected $refreshTokenExpiresInSeconds;


    /**
     * TDAmeritradeAPI constructor.
     * @param string|NULL $userName
     * @param string|NULL $accessToken
     * @param string|NULL $refreshToken
     * @param int|NULL $refreshTokenExpiresInSeconds
     * @param bool $debug
     */
    public function __construct( string $userName = NULL,
                                 string $accessToken = NULL,
                                 string $refreshToken = NULL,
                                 int $refreshTokenExpiresInSeconds = NULL,
                                 bool $debug = FALSE ) {
        $this->userName                     = $userName;
        $this->accessToken                  = $accessToken;
        $this->refreshToken                 = $refreshToken;
        $this->refreshTokenExpiresInSeconds = $refreshTokenExpiresInSeconds;
        $this->guzzle                       = $this->createGuzzleClient( $this->accessToken, $debug );
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
    public function getAccessToken(): string {
        return $this->accessToken;
    }

    public function getRefreshToken(): string {
        return $this->refreshToken;
    }

    public function getRefreshTokenExpiresInSeconds(): ?int {
        return $this->refreshTokenExpiresInSeconds;
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
                'fields' => 'positions,orders',
            ],
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
                'fields' => 'positions,orders',
            ],
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
     * @param string $orderType Ex: MARKET, LIMIT
     * @param string $instruction Ex: BUY
     * @param float|NULL $price
     * @return bool
     * @throws BaseClientException
     * @throws BaseServerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @see https://developer.tdameritrade.com/account-access/apis/post/accounts/%7BaccountId%7D/orders-0
     * @see https://developer.tdameritrade.com/content/place-order-samples
     * @see http://docs.guzzlephp.org/en/latest/request-options.html#json
     */
    public function placeOrder( string $accountId,
                                string $ticker,
                                int $quantity,
                                string $quantityType,
                                string $orderType,
                                string $instruction,
                                float $price = NULL ): bool {
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

        // This parameter is not required for MARKET orderType
        if ( $price ):
            $orderArray[ 'price' ] = $price;
        endif;

        try {
            $this->guzzle->request( 'POST', $uri, [ RequestOptions::JSON => $orderArray ] );
            return TRUE;
        } catch
        ( \GuzzleHttp\Exception\ClientException $exception ) {
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
     * @param int|null $maxResults
     * @param string|null $fromEnteredTime Ex: 2020-01-01
     * @param string|null $toEnteredTime Ex: 2020-12-31
     * @throws BaseClientException
     * @throws BaseServerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getOrdersByQuery( string $accountId,
                                      int $maxResults = NULL,
                                      string $fromEnteredTime = NULL,
                                      string $toEnteredTime = NULL ) {
        $uri     = 'v1/orders';
        $options = [
            'query' => [
                'accountId' => $accountId,
            ],
        ];

        if ( $maxResults ):
            $options[ 'query' ][ 'maxResults' ] = $maxResults;
        endif;

        if ( $fromEnteredTime ):
            $options[ 'query' ][ 'fromEnteredTime' ] = $fromEnteredTime;
        endif;

        if ( $toEnteredTime ):
            $options[ 'query' ][ 'toEnteredTime' ] = $toEnteredTime;
        endif;


        try {
            $response = $this->guzzle->request( 'GET', $uri, $options );
            $body     = $response->getBody();
            $json     = json_decode( $body, TRUE );
            return new Orders( $json );
        } catch
        ( \GuzzleHttp\Exception\ClientException $exception ) {
            throw ClientExceptionFactory::create( $exception, [

            ] );
        } catch ( \GuzzleHttp\Exception\ServerException $exception ) {
            throw new BaseServerException( $exception->getMessage(), $exception->getCode(), $exception, [

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


    /**
     * @param string $accountId
     * @param string $ticker
     * @param int $quantity
     * @param float $price
     * @return bool
     * @throws BaseClientException
     * @throws BaseServerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sellStockSharesLimitPrice( string $accountId, string $ticker, int $quantity, float $price ): bool {

        // Orders above $1 can be entered in no more than 2 decimals; orders below $1 can be entered in no more than 4 decimals.
        // This is an error that is returned by TDA.
        if ( 1 > $price ):
            $price = round( $price, 4 );
        elseif ( 1 < $price ):
            $price = round( $price, 2 );
        endif;

        return $this->placeOrder( $accountId,
                                  $ticker,
                                  $quantity,
                                  'SHARES',
                                  'LIMIT',
                                  'SELL',
                                  $price );
    }


    /**
     * @param string $accountId
     * @param string $ticker
     * @param int $quantity
     * @param float $price
     * @return bool
     * @throws BaseClientException
     * @throws BaseServerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @url https://developer.tdameritrade.com/content/place-order-samples
     * Search for "Conditional Order: One Triggers Another"
     */
    public function placeFirstTriggerSequenceOrder( string $accountId,
                                                    string $ticker,
                                                    int $quantity,
                                                    float $exitAfterThisPercentIncrease ): bool {
        return $this->placeOrder( $accountId,
                                  $ticker,
                                  $quantity,
                                  'SHARES',
                                  'LIMIT',
                                  'SELL',
                                  $price );
    }


    /**
     * @param string $accountId
     * @param string $ticker
     * @param float $price
     * @return bool
     * @throws BaseClientException
     * @throws BaseServerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @TODO this does not work right. 'ALL_SHARES' is passed but TDA still wants a quantity.
     */
    public function sellStockAllSharesLimitPrice( string $accountId, string $ticker, float $price ): bool {
        $uri        = 'v1/accounts/' . $accountId . '/orders';
        $orderArray = [
            'orderType'          => 'LIMIT',
            'session'            => 'NORMAL',
            'duration'           => 'DAY',
            'orderStrategyType'  => 'SINGLE',
            'price'              => $price,
            'orderLegCollection' => [ [
                                          'orderLegType' => 'EQUITY',
                                          'instruction'  => 'SELL',
                                          'quantityType' => 'ALL_SHARES',
                                          'instrument'   => [
                                              'symbol'    => $ticker,
                                              'assetType' => 'EQUITY',
                                          ] ],
            ],
        ];

        try {
            $this->guzzle->request( 'POST', $uri, [ RequestOptions::JSON => $orderArray, 'debug' => TRUE ] );
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


    /**
     * @param string $accountId
     * @param float $minPercentProfit
     * @return array[]
     * @throws BaseClientException
     * @throws BaseServerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function placeSellLimitOrdersOverPercentProfitOnAllPositions( string $accountId,
                                                                         float $minPercentProfit ): array {
        $orders  = [
            'placed'    => [],
            'notPlaced' => [],
        ];
        $account = $this->getAccount( $accountId );
        /**
         * @var Position $position
         */
        foreach ( $account->positions as $position ):
            $ticker     = $position->instrument[ 'symbol' ];
            $limitPrice = $position->averagePrice * ( 1 + $minPercentProfit );
            $quantity   = $position->longQuantity;

            $placed     = $this->sellStockSharesLimitPrice( $accountId,
                                                            $ticker,
                                                            $quantity,
                                                            $limitPrice );
            if ( $placed ):
                $orders[ 'placed' ][] = [
                    'accountId'  => $accountId,
                    'ticker'     => $ticker,
                    'quantity'   => $quantity,
                    'limitPrice' => $limitPrice,
                ];
            else:
                $orders[ 'notPlaced' ][] = [
                    'accountId'  => $accountId,
                    'ticker'     => $ticker,
                    'quantity'   => $quantity,
                    'limitPrice' => $limitPrice,
                ];
            endif;
        endforeach;
        return $orders;
    }

}