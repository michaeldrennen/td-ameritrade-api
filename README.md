# td-ameritrade-api
![Packagist](https://img.shields.io/packagist/dt/michaeldrennen/td-ameritrade-api) [![GitHub issues](https://img.shields.io/github/issues/michaeldrennen/td-ameritrade-api)](https://github.com/michaeldrennen/td-ameritrade-api/issues) [![GitHub stars](https://img.shields.io/github/stars/michaeldrennen/td-ameritrade-api)](https://github.com/michaeldrennen/td-ameritrade-api/stargazers) [![GitHub forks](https://img.shields.io/github/forks/michaeldrennen/td-ameritrade-api)](https://github.com/michaeldrennen/td-ameritrade-api/network) [![GitHub license](https://img.shields.io/github/license/michaeldrennen/td-ameritrade-api)](https://github.com/michaeldrennen/td-ameritrade-api/blob/master/LICENSE) 
 
A PHP library used to interact with the TD Ameritrade API

Visit the link below to set up your developer account with TD Ameritrade, and get information on how to authenticate with their API.
https://developer.tdameritrade.com/

```php
// How to create a TDAmeritrade API client.
$userName                     = 'joeuser123';
$accessToken                  = 'stringFromTDAmeritradeAuthentication';
$refreshToken                 = 'anotherStringFromTDAmeritradeAuthentication';
$refreshTokenExpiresInSeconds = intFromTDAmeritradeAuthentication;
$debug                        = false;
$tdaClient = TDAmeritradeAPI( $userName,
                              $accessToken,
                              $refreshToken,
                              $refreshTokenExpiresInSeconds,
                              $debug );
```

```php
// Once you have a client, you can buy stocks at market price.
$accountId = 123456789;
$ticker    = 'LODE';
$quantity  = 1;

try {
   $tdaClient->buyStockSharesMarketPrice($accountId, $ticker, $quantity);
} catch (Exception $exception) {
   echo $exception->getMessage();
}

// Now, log into your TDAmeritrade account online, and you should see a new order.

// To sell some shares at the market price...
try {
   $tdaClient->sellStockSharesMarketPrice($accountId, $ticker, $quantity);
} catch (Exception $exception) {
   echo $exception->getMessage();
}
```

You can look through the TDAmeritradeAPI.php file for a complete list of the actions I have written code for.

Happy trading!

-Mike