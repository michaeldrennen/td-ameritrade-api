<?php

namespace MichaelDrennen\TDAmeritradeAPI;

class TDAmeritradeAPI {

    /**
     * @var string The consumer key for your TD Ameritrade API app.
     * @see https://developer.tdameritrade.com/user/me/apps
     */
    protected $oauthConsumerKey;

    public function __construct(string $oauthConsumerKey) {

        $this->oauthConsumerKey = $oauthConsumerKey;
    }
}