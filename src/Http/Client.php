<?php

namespace TechKat\BackblazeB2\Http;

use TechKat\BackblazeB2\ErrorHandler;
use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;

class Client extends GuzzleClient
{
  protected $client;
  protected $retryLimit = 10;
  protected $retryWaitSeconds = 10;

  public function __construct(array $guzzleOptions)
  {
    parent::__construct($guzzleOptions);
  }

  /*
  |--------------------------------------------------------------------------
  | Build GuzzleHTTP Client Request
  |--------------------------------------------------------------------------
  |
  | Use the GuzzleClient package to build the request and parse the response back to the BackBlaze B2 SDK Client.
  | Due to BackBlaze B2's API sometimes throwing errors out of our control, the HttpClient will attempt to
  | retry up to 10 times before abandoning the atempt and throwing an Exception. Each attempt will increase
  | the retry timeout limit to give the BackBlaze B2 API time to recover.
  |
  |--------------------------------------------------------------------------
  | @param  (string) $method | Default: GET
  | @param  (string) $uri | Default: null
  | @param  (array) $options | Default: (empty)
  | @return (string) | Exception
  |--------------------------------------------------------------------------
  |
  */
  public function request(string $method = 'GET', $uri = null, array $options = []): ResponseInterface
  {
    /*
     * Build HTTP request and store to a variable.
    */
    $response = parent::request($method, $uri, $options);

    /*
     * Set retries to 0
     * Set retry wait
    */
    $retries = 0;
    $wait = $this->retryWaitSeconds;

    /*
    * Store HTTP response's status code to variable.
    */
    $statusCode = $response->getStatusCode();

    /*
     * While loop of retries.
     * If retries variable is above the retryLimit, abandon white loop and continue.
    */
    while($statusCode == 503 && $this->retryLimit > $retries)
    {
      /*
       * Increase retries count and put script to sleep for X amount of seconds.
      */
      $retries++;
      sleep($wait);

      /*
       * HTTP request will attempt again. If status code return 200, break from loop.
      */
      $response = parent::request($method, $uri, $options);
      $statusCode = $response->getStatusCode();
      if($statusCode == 200) break;

      /*
       * If we get this far, BackBlaze B2 API is still returning a status code
       * that is not 200. Increase wait time slightly.
      */
      $wait *= 1.2;
    }

    /*
     * If HTTP status code from response is still anything but 200,
     * and we have exceeded our retry limit, throw an exception.
    */
    if($statusCode != 200)
    {
      ErrorHandler::handleErrorResponse($response);
    }

    /*
     * Return the healthy response.
     * ErrorHandler will override and return its own response.
    */
    return $response;
  }
}
