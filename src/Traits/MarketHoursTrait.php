<?php

namespace MichaelDrennen\TDAmeritradeAPI\Traits;


use Carbon\Carbon;
use MichaelDrennen\Calendar\Calendar;
use MichaelDrennen\TDAmeritradeAPI\Responses\MarketHours;

trait MarketHoursTrait {
    use BaseTrait;


    /**
     * @param Carbon $date
     * @param string $market
     * @return MarketHours
     * @throws \Exception
     * @see https://developer.tdameritrade.com/market-hours/apis/get/marketdata/%7Bmarket%7D/hours
     */
    public function getEquityMarketHours( Carbon $date ): MarketHours {
        $uri = 'v1/marketdata/EQUITY/hours';

        $now  = Carbon::now( 'UTC' );
        $date = $date->copy()->setTimezone( 'UTC' );

        $nowUnix  = $now->copy()->timestamp;
        $dateUnix = $date->copy()->timestamp;

        if ( $dateUnix < $nowUnix ):
            throw new \Exception( "You need to enter a date in the future. Past dates return an error from their API." );
        endif;

        // Valid ISO-8601 formats are : yyyy-MM-dd and yyyy-MM-dd'T'HH:mm:ssz."
        $formattedDate = $date->toIso8601ZuluString();

        $options  = [
            'query' => [
                'date' => $formattedDate,
            ],
        ];
        $response = $this->guzzle->request( 'GET', $uri, $options );
        $body     = $response->getBody();
        $json     = json_decode( $body, TRUE );

        if ( isset( $json[ 'equity' ][ 'EQ' ] ) ):
            return new MarketHours( $json[ 'equity' ][ 'EQ' ] );
        elseif ( isset( $json[ 'equity' ][ 'equity' ] ) ):
            return new MarketHours( $json[ 'equity' ][ 'equity' ] );
        endif;
        throw new \Exception( "Take a look at the JSON returned from their API. I'm seeing a new index in the array." );
    }

    public function getNextMarketHours(): MarketHours {
        $now            = Carbon::now( 'America/New_York' );
        $nextMarketOpen = Calendar::getNextUSMarketOpen( $now );
        return $this->getEquityMarketHours( $nextMarketOpen );
    }

}