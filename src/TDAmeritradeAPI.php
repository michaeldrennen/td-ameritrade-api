<?php

namespace MichaelDrennen\TDAmeritradeAPI;

use GuzzleHttp\Client;

class TDAmeritradeAPI {

    /**
     * @var string The consumer key for your TD Ameritrade API app.
     * @see https://developer.tdameritrade.com/user/me/apps
     */
    protected $oauthConsumerKey;

    protected $userName;
    protected $password;

    protected $callbackUrl;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    public function __construct( string $oauthConsumerKey, string $userName, string $password, string $callbackUrl ) {

        $this->oauthConsumerKey = $oauthConsumerKey;
        $this->userName         = $userName;
        $this->password         = $password;
        $this->callbackUrl      = $callbackUrl;
        $this->guzzle           = $this->createGuzzleClient();

    }

    /**
     * @param string|NULL $token
     * @return \GuzzleHttp\Client
     */
    protected function createGuzzleClient( string $token = NULL ): Client {

        $headers             = [];
        $headers[ 'Accept' ] = 'application/json';
        if ( $token ):
            $headers[ 'Authorization' ] = 'Bearer ' . $token;
        endif;

        $options = [

            'allow_redirects' => [
                'strict' => TRUE,
            ],
            'base_uri'        => 'https://auth.tdameritrade.com',
            'headers'         => $headers ];
        return new Client( $options );
    }


    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * https://auth.tdameritrade.com/auth?response_type=code&redirect_uri={Callback URL}&client_id={Consumer Key}@AMER.OAUTHAP
     */
    public function authenticate() {
        $html = $this->getHtmlFromAmeritradeLoginPage();
        $inputs = $this->getInputsFromAmeritradeLoginPage($html);


    }

    protected function getHtmlFromAmeritradeLoginPage(){
        $url = '/auth';

        $options = [
            'query' => [
                'response_type' => 'code',
                'redirect_uri'  => $this->callbackUrl,
                'client_id'     => $this->oauthConsumerKey . '@AMER.OAUTHAP',
            ],
        ];

        $response = $this->guzzle->request( 'GET', $url, $options );
        $body     = $response->getBody();
        $contents = $body->getContents();

        return $contents;
    }

    protected function getInputsFromAmeritradeLoginPage(string $htmlOfLoginPage){
        var_dump($htmlOfLoginPage);
        $dom = new \DOMDocument();
        $dom->loadHTML($htmlOfLoginPage);
        $inputs = $dom->getElementsByTagName('input');
        foreach ($inputs as $input):
            var_dump($input);
        endforeach;
        return $inputs;
    }
}