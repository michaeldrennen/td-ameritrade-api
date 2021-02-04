<?php

namespace MichaelDrennen\TDAmeritrade\Tests;

use MichaelDrennen\TDAmeritradeAPI\Authenticator;
use MichaelDrennen\TDAmeritradeAPI\TDAmeritradeAPI;
use PHPUnit\Framework\TestCase;

abstract class AbstractParentTest extends TestCase {

    public $refreshToken = NULL;

    /**
     * @param string|NULL $refreshToken
     * @return TDAmeritradeAPI
     */
    protected function getTDAmeritradeAPIInstance( string $refreshToken = NULL ): TDAmeritradeAPI {
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

        //$refreshTokenExpiresInSeconds = 60 * 60 * 24
        $refreshTokenExpiresInSeconds = NULL;

        $chromePath = getenv( 'CHROME_PATH' );

        $authenticator = new Authenticator( $oauthConsumerKey,
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
                                            $answer_4,
                                            $refreshToken,
                                            $refreshTokenExpiresInSeconds,
                                            $chromePath );

        $tdAmeritradeApi = $authenticator->authenticate_v2();
        $this->refreshToken = $tdAmeritradeApi->getRefreshToken();

        return $tdAmeritradeApi;
    }
}