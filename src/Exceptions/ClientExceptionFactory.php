<?php

namespace MichaelDrennen\TDAmeritradeAPI\Exceptions;


class ClientExceptionFactory {

    public static function create( \GuzzleHttp\Exception\ClientException $exception, array $metaInfo ): BaseClientException {
        $response             = $exception->getResponse();
        $responseBodyAsString = $response->getBody()->getContents();
        $json                 = \GuzzleHttp\json_decode( $responseBodyAsString, TRUE );

        if ( FALSE === isset( $json[ 'error' ] ) ):
            return new BaseClientException( $responseBodyAsString, $exception->getCode(), $exception, $metaInfo );
        endif;

        switch ( $json[ 'error' ] ):
            case ' Please enter a valid symbol.':
                return new InvalidSymbolException( $json[ 'error' ], $exception->getCode(), $exception, $metaInfo );
            default:
                return new BaseClientException( $responseBodyAsString, $exception->getCode(), $exception, $metaInfo );
        endswitch;
    }
}
