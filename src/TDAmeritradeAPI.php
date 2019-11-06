<?php

namespace MichaelDrennen\TDAmeritradeAPI;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class TDAmeritradeAPI {

    const BASE_URI = 'https://auth.tdameritrade.com';

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
            'base_uri'        => self::BASE_URI,
            'headers'         => $headers ];
        return new Client( $options );
    }


    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function authenticate() {
        $html       = $this->getHtmlFromAmeritradeLoginPage();
        $formAction = $this->getFormActionFromAmeritradeLoginPage( $html );
        $inputs     = $this->getInputsFromAmeritradeLoginPage( $html );
        $inputs     = $this->addAdditionalFieldsToInputs( $inputs );
        //print_r( $inputs );



        $options = [
            'form_params' => $inputs,
            'query'       => [
                'response_type' => 'code',
                'redirect_uri'  => $this->callbackUrl,
                'client_id'     => $this->oauthConsumerKey . '@AMER.OAUTHAP',
            ],
        ];



        $response = $this->guzzle->request( 'POST', $formAction, $options );
        $body     = $response->getBody();
        $contents = $body->getContents();

        var_dump( $contents );

        return $contents;

    }

    protected function getHtmlFromAmeritradeLoginPage() {
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


    /**
     * @param string $htmlOfLoginPage
     * @return string
     * @throws \Exception
     */
    protected function getFormActionFromAmeritradeLoginPage( string $htmlOfLoginPage ): string {
        $crawler = new Crawler( $htmlOfLoginPage );
        $crawler = $crawler->filter( 'form' );
        foreach ( $crawler as $domElenment ):
            return $domElenment->getAttribute( 'action' );
        endforeach;
        throw new \Exception( "Form action not found on Ameritrade Login Page" );
    }


    //
    protected function getInputsFromAmeritradeLoginPage( string $htmlOfLoginPage ): array {
        $inputs  = [];
        $crawler = new Crawler( $htmlOfLoginPage );
        $crawler = $crawler->filter( 'input' );
        foreach ( $crawler as $domElement ):
            $inputs[ $domElement->getAttribute( 'name' ) ] = $domElement->getAttribute( 'value' );
        endforeach;

        return $inputs;
    }

    protected function addAdditionalFieldsToInputs( array $inputs ): array {
        $inputs[ 'su_username' ] = $this->userName;
        $inputs[ 'su_password' ] = $this->password;

        /**
         * The device signature
         */
//        $inputs[ 'fp_cfp' ]         = getenv( 'TDAMERITRADE_FP_CFP' );
//        $inputs[ 'fp_fp2DeviceId' ] = getenv( 'TDAMERITRADE_FP_FP2_DEVICE_ID' );
//        $inputs[ 'fp_browser' ]     = getenv( 'TDAMERITRADE_FP_BROWSER' );
//        $inputs[ 'fp_screen' ]      = getenv( 'TDAMERITRADE_FP_SCREEN' );
//        $inputs[ 'fp_timezone' ]    = getenv( 'TDAMERITRADE_FP_TIMEZONE' );
//        $inputs[ 'fp_language' ]    = getenv( 'TDAMERITRADE_FP_LANGUAGE' );
//        $inputs[ 'fp_java' ]        = getenv( 'TDAMERITRADE_FP_JAVA' );
//        $inputs[ 'fp_cookie' ]      = getenv( 'TDAMERITRADE_FP_COOKIE' );

        $inputs = array_filter( $inputs );

        return $inputs;
    }

}