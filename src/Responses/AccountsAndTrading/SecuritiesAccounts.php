<?php

namespace MichaelDrennen\TDAmeritradeAPI\Responses\AccountsAndTrading;


/**
 * Class SecuritiesAccounts
 * @package MichaelDrennen\TDAmeritradeAPI\Responses
 */
class SecuritiesAccounts {


    /**
     * @var array An array of SecurityAccount objects.
     */
    public $accounts = [];


    public function __construct( array $arrayOfAccountValues ) {

//        print_r($arrayOfAccountValues);

        foreach ( $arrayOfAccountValues as $accountValues ):
            // They index these securities accounts a little weird, hence the 'securitiesAccount' index you see below.
            $this->accounts[] = new SecuritiesAccount( $accountValues['securitiesAccount'] );
        endforeach;

    }
}