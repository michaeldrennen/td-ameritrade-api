<?php

namespace MichaelDrennen\TDAmeritradeAPI\Responses\AccountsAndTrading;


/**
 * Class SecuritiesAccount
 * @package MichaelDrennen\TDAmeritradeAPI\Responses
 */
class SecuritiesAccount {


    /**
     * @var string Ex: MARGIN
     */
    public $type;


    /**
     * It looks likes it'll always be an integer, but storing as a string in case there are examples where it is a
     * zero padded number.
     * @var string Ex: 123456789
     */
    public $accountId;


    /**
     * @var integer ?
     */
    public $roundTrips;

    /**
     * @var boolean
     */
    public $isDayTrader;


    /**
     * @var boolean ?
     */
    public $isClosingOnlyRestricted;


    /**
     * @var InitialBalances
     */
    public $initialBalances;

    /**
     * @var CurrentBalances
     */
    public $currentBalances;

    /**
     * @var ProjectedBalances
     */
    public $projectedBalances;

    /**
     * @var array
     */
    public $positions;


    /**
     * @var array
     */
    public $orders;

    public function __construct( array $values ) {
        $this->type                    = (string)$values[ 'type' ];
        $this->accountId               = (string)$values[ 'accountId' ];
        $this->roundTrips              = (int)$values[ 'roundTrips' ];
        $this->isDayTrader             = (bool)$values[ 'isDayTrader' ];
        $this->isClosingOnlyRestricted = (bool)$values[ 'isClosingOnlyRestricted' ];
        $this->initialBalances         = new InitialBalances( $values[ 'initialBalances' ] );
        $this->currentBalances         = new CurrentBalances( $values[ 'currentBalances' ] );
        $this->projectedBalances       = new ProjectedBalances( $values[ 'projectedBalances' ] );

        // These can be optionally returned.
        if ( isset( $values[ 'positions' ] ) ):
            foreach ( $values[ 'positions' ] as $i => $positionData ):
                $this->positions[] = new Position( $positionData );
            endforeach;
        endif;

        // These can be optionally returned.
        if ( isset( $values[ 'orderStrategies' ] ) ):
            foreach ( $values[ 'orderStrategies' ] as $i => $orderData ):
                $this->orders[] = new Order( $orderData );
            endforeach;
        endif;


    }
}