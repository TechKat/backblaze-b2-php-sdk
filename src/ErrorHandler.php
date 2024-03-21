<?php

namespace TechKat\BackblazeB2;

use TechKat\BackblazeB2\Exceptions\B2Exception;

use GuzzleHttp\Psr7\Response;

class ErrorHandler
{
  protected static $mappings = [

    // Status Code: 400
    'auth_token_limit'               => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\AuthTokenLimitException::class,
    'bad_bucket_id'                  => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\BadBucketIdException::class,
    'bad_request'                    => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\BadRequestException::class,
    'cannot_delete_non_empty_bucket' => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\BucketNotEmptyException::class,
    'duplicate_bucket_name'          => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\BucketAlreadyExistsException::class,
    'file_not_present'               => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\FileNotPresentException::class,
    'invalid_bucket_id'              => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\InvalidBucketIdException::class,
    'invalid_file_id'                => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\InvalidFileIdException::class,
    'metadata_exceeded'              => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\MetadataExceededException::class,
    'out_of_range'                   => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\OutOfRangeException::class,
    'source_too_large'               => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\SourceFileTooLargeException::class,
    'too_many_buckets'               => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\TooManyBucketsException::class,

    // Status Code: 401
    'bad_auth_token'                 => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\AuthTokenNotValidException::class,
    'expired_auth_token'             => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\AuthTokenExpiredException::class,
    'unauthorized'                   => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\UnauthorizedCallException::class,

    // Status Code: 402

    // Status Code: 403
    'access_denied'                  => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\AccessDeniedException::Class,
    'cap_exceeded'                   => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\UsageCapExceededException::Class,
    'storage_cap_exceeded'           => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\StorageCapExceededException::Class,
    'transaction_cap_exceeded'       => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\TransactionCapExceededException::class,

    // Status Code: 404
    'not_found'                      => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\NotFoundException::class,

    // Status Code: 405
    'method_not_allowed'             => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\MethodNotAllowedException::class,

    // Status Code: 408
    'request_timeout'                => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\RequestTimeoutException::class,

    // Status Code: 416
    'range_not_satisfiable'          => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\RangeNotSatisfiableException::class,

    // Status Code: 429
    'too_many_requests'              => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\TooManyRequestsException::class,

    // Status Code: 503
    'service_unavailable'            => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\ServiceUnavailableException::class,

    'bad_json'                       => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\BadJsonException::class,
    'bad_value'                      => \TechKat\BackblazeB2\Exceptions\BackblazeCodes\BadValueException::class,
  ];

  /*
  |--------------------------------------------------------------------------
  | Handle Backblaze B2 Error Responses
  |--------------------------------------------------------------------------
  |
  | Errors are unavoidable. But there should be an easy way to catch them.
  | When an error is captured from a BackBlaze B2 API request, it will be
  | handled in a way that can map the error to the particular error the API
  | returned with.
  |
  |--------------------------------------------------------------------------
  | @param  Response $response
  | @return Exception
  |--------------------------------------------------------------------------
  |
  */
  public static function handleErrorResponse(Response $response)
  {
    $responseJson = json_decode($response->getBody(), true);

    list($code, $message) = [$responseJson['code'], $responseJson['message']];

    $errorIsMapped = isset(self::$mappings[$code]);
    $exceptionClass = ($errorIsMapped) ? self::$mappings[$code] : B2Exception::class;

    throw new $exceptionClass(
      sprintf('Received error from B2: %s. Code: %s', $message, $code)
    );
  }
}
