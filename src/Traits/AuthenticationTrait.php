<?php

namespace MichaelDrennen\TDAmeritradeAPI\Traits;


use Carbon\Carbon;
use MichaelDrennen\Calendar\Calendar;
use MichaelDrennen\TDAmeritradeAPI\Responses\MarketHours;

trait AuthenticationTrait {
    use BaseTrait;


    /**
     * @param string $oauthConsumerKey
     * @param string $refreshToken Given to you when you first authenticate.
     * @return string Your new access code.
     * @see https://developer.tdameritrade.com/authentication/apis/post/token-0
     */
    public function postAccessTokenRefreshToken( string $oauthConsumerKey, string $refreshToken ): string {
        $uri = '/v1/oauth2/token';


        $options  = [
            'form_params' => [
                'grant_type'    => 'refresh_token',
                'refresh_token' => $refreshToken,       // Required
                'client_id'     => $oauthConsumerKey,   // Required
            ],
        ];
        $response = $this->guzzle->request( 'POST', $uri, $options );
        $body     = $response->getBody();
        $json     = json_decode( $body, TRUE );

        $accessToken = $json['access_token'];

        return $accessToken;
    }

}