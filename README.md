# PHP-Bitly
PHP SDK for generating short URLs using the Bitly web service.

### Supports
- Shortening a long URL

### Requirements
- [PHP-RemoteRequests](https://github.com/onassar/PHP-RemoteRequests)

### Sample shorten call
``` php
$client = new onassar\Bitly\Bitly();
$client->setToken('***');
$client->setTimeout(10);
$longURL = 'https://google.com/';
$shortURL = $client->getShortURL($longURL) ?? null;
print_r($shortURL);
exit(0);
```
