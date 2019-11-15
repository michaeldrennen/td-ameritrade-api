<?php

namespace MichaelDrennen\TDAmeritradeAPI;

use HeadlessChromium\BrowserFactory;
use HeadlessChromium\Exception\NavigationExpired;
use HeadlessChromium\Exception\OperationTimedOut;
use HeadlessChromium\Page;
use Exception;

class Authenticator {

    use APIClientTrait;

    const AUTH_URI = 'https://auth.tdameritrade.com/';


    /**
     * @var string The consumer key for your TD Ameritrade API app.
     * @see https://developer.tdameritrade.com/user/me/apps
     */
    protected $oauthConsumerKey;

    /**
     * @var string The TD Ameritrade username to be authenticated.
     */
    protected $userName;

    /**
     * @var string The password for the aforementioned TD Ameritrade user name.
     */
    protected $password;


    /**
     * @var string The URL of your application that will receive the CODE from TD Ameritrades API.
     */
    protected $callbackUrl;

    protected $securityQuestions = [];


    public function __construct( string $oauthConsumerKey,
                                 string $userName,
                                 string $password,
                                 string $callbackUrl,
                                 string $question1,
                                 string $answer1,
                                 string $question2,
                                 string $answer2,
                                 string $question3,
                                 string $answer3,
                                 string $question4,
                                 string $answer4
    ) {

        $this->oauthConsumerKey = $oauthConsumerKey;
        $this->userName         = $userName;
        $this->password         = $password;
        $this->callbackUrl      = $callbackUrl;

        $this->securityQuestions[ $question1 ] = $answer1;
        $this->securityQuestions[ $question2 ] = $answer2;
        $this->securityQuestions[ $question3 ] = $answer3;
        $this->securityQuestions[ $question4 ] = $answer4;
    }


    public function authenticate() {
        $loginUrl       = $this->getLoginUrl( $this->callbackUrl, $this->oauthConsumerKey );
        $browserFactory = new BrowserFactory();

        // starts headless chrome
        $browser = $browserFactory->createBrowser( [
                                                       'headless'        => TRUE,         // disable headless mode
                                                       'connectionDelay' => 0.8,           // add 0.8 second of delay between each instruction sent to chrome,
                                                       //'debugLogger'     => 'php://stdout' // will enable verbose mode
                                                   ]
        );

        // creates a new page and navigate to an url
        $page = $browser->createPage();
        $page->navigate( $loginUrl )->waitForNavigation();

        // get page title
        $page->evaluate( "document.querySelector('#username').value = '" . $this->userName . "';" );
        $page->evaluate( "document.querySelector('#password').value = '" . $this->password . "';" );
        $evaluation = $page->evaluate( "document.querySelector('#authform').submit();" );

        $evaluation->waitForPageReload();

        $postLoginPageInnerHTML = $page->evaluate( 'document.body.innerHTML' )->getReturnValue();


        if ( $this->textChallengePresented( $postLoginPageInnerHTML ) ):
            $code = $this->processTextChallenge( $page );
        else:
            $page->evaluate( 'console.log("Text challenge NOT presented")' );

            $code = $this->clickTheAllowButtonAndReturnTheCode( $page );
        endif;


        $token = $this->getTokenFromCode( $code );

        return $token;
    }

    protected function getLoginUrl( string $callbackUri, string $oauthConsumerKey ) {
        return self::AUTH_URI . 'auth?response_type=code&redirect_uri=' . $callbackUri . '&client_id=' . $oauthConsumerKey . '@AMER.OAUTHAP';
    }


    /**
     * @param string $postLoginPageInnerHTML
     * @return bool
     */
    protected function textChallengePresented( string $postLoginPageInnerHTML ): bool {
        $challengeStringText = "Can't get the text message?";
        if ( FALSE !== stripos( $postLoginPageInnerHTML, $challengeStringText ) ):
            return TRUE;
        endif;
        return FALSE;
    }


    /**
     * @param Page $page
     * @return string
     * @throws NavigationExpired
     * @throws OperationTimedOut
     * @throws \HeadlessChromium\Exception\CommunicationException
     * @throws \HeadlessChromium\Exception\EvaluationFailed
     */
    protected function processTextChallenge( Page &$page ): string {

        try {
            // Send a click t the first element on the page with a class of .alt_cta
            // This should be the link with the text "Can't get the text message?"
            $evaluation = $page->evaluate( "document.querySelector('.alt_cta').click();" );
            $evaluation->waitForPageReload();
            $page->evaluate( 'console.log("We have made it to the challenge page.")' );
        } catch ( OperationTimedOut $e ) {
            // too long to load
            var_dump( $e->getMessage() );
            throw $e;
        } catch ( NavigationExpired $e ) {
            // An other page was loaded
            var_dump( $e->getMessage() );
            throw $e;
        }

        $htmlFromChallengePage = $page->evaluate( 'document.body.innerHTML' )->getReturnValue();
        $answer                = $this->getChallengeAnswerFromLoginPageHTML( $htmlFromChallengePage );
        $page->evaluate( "document.querySelector('#secretquestion').value = '" . $answer . "';" );
        $page->evaluate( "document.querySelector('#accept').disabled = false;" );


        try {
            $evaluation = $page->evaluate( "document.querySelector('#authform').submit();" );
            $evaluation->waitForPageReload();
            $page->evaluate( 'console.log("We have made it to the ALLOW page.")' );
        } catch ( OperationTimedOut $e ) {
            // too long to load
            var_dump( $e->getMessage() );
            throw $e;
        } catch ( NavigationExpired $e ) {
            // An other page was loaded
            var_dump( $e->getMessage() );
            throw $e;
        }


        return $this->clickTheAllowButtonAndReturnTheCode( $page );
    }


    /**
     *
     * @param string $htmlFromLoginPage
     * @return string
     * @throws \Exception
     */
    protected function getChallengeAnswerFromLoginPageHTML( string $htmlFromLoginPage ): string {
        foreach ( $this->securityQuestions as $question => $answer ):
            if ( $this->questionWasFoundInLoginPageHTML( $question, $htmlFromLoginPage ) ):
                return $answer;
            endif;
        endforeach;
        throw new Exception( "Unable to find the question string in the HTML from the login page." );
    }


    /**
     * A little helper function that checks for the question string in the HTML scraped from the login page.
     * @param string $question
     * @param string $htmlFromLoginPage
     * @return bool
     */
    protected function questionWasFoundInLoginPageHTML( string $question, string $htmlFromLoginPage ): bool {
        if ( FALSE !== stripos( $htmlFromLoginPage, $question ) ):
            return TRUE;
        endif;
        return FALSE;
    }


    /**
     * @param Page $page
     * @return string
     * @throws NavigationExpired
     * @throws OperationTimedOut
     * @throws \HeadlessChromium\Exception\CommunicationException
     * @throws \HeadlessChromium\Exception\EvaluationFailed
     */
    protected function clickTheAllowButtonAndReturnTheCode( Page &$page ): string {
        try {
            $evaluation = $page->evaluate( "document.querySelector('#accept').click();" );
            $evaluation->waitForPageReload();
            $page->evaluate( 'console.log("We have made it to the ALLOW page.")' );
        } catch ( OperationTimedOut $e ) {
            // too long to load
            var_dump( $e->getMessage() );
            throw $e;
        } catch ( NavigationExpired $e ) {
            // An other page was loaded
            var_dump( $e->getMessage() );
            throw $e;
        }

        $jsonString = $page->evaluate( 'document.body.innerText' )->getReturnValue();
        $json       = \GuzzleHttp\json_decode( $jsonString, TRUE );

        return (string)$json[ 'code' ];
    }


    /**
     * @param string $code
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @see https://developer.tdameritrade.com/authentication/apis/post/token-0
     */
    protected function getTokenFromCode( string $code ): string {

        $guzzle = $this->createGuzzleClient();

        // https://api.tdameritrade.com/v1/oauth2/token
        $uri = 'v1/oauth2/token';

        $options  = [

            'form_params' => [
                'grant_type'   => 'authorization_code',
                'code'         => $code,
                'client_id'    => $this->oauthConsumerKey,
                'redirect_uri' => $this->callbackUrl,
            ],
        ];
        $response = $guzzle->request( 'POST', $uri, $options );
        $body     = $response->getBody();

        $json = \GuzzleHttp\json_decode( $body, TRUE );

        return $json[ 'access_token' ];
    }

}