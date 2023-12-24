<?php

namespace TechKat\BackblazeB2\Helpers;

trait Validation
{
  /*
  |--------------------------------------------------------------------------
  | Clean an array of null values
  |--------------------------------------------------------------------------
  |
  | If the array contains null values, remove them as BackBlaze B2 API does not take null values.
  |
  |--------------------------------------------------------------------------
  | @param  (array) $requestOptions
  | @param  (array) $definedOptions
  | @return (array)
  |--------------------------------------------------------------------------
  |
  */
  protected function cleanArrayOfNulls(array $requestOptions, array $definedOptions): array
  {
    foreach($requestOptions as $key => $value)
    {
      if(isset($definedOptions[$key]))
      {
        $requestOptions[$key] = $definedOptions[$key];
      }
    }

    return array_filter($requestOptions);
  }

  /*
  |--------------------------------------------------------------------------
  | Check if array contains mandatory keys/values.
  |--------------------------------------------------------------------------
  |
  | Some traits will have mandatory options.
  | This validation helper checks if all the mandatory options are set.
  |
  |--------------------------------------------------------------------------
  | @param  (array) $haystack
  | @param  (array) $needles
  | @return (bool) true|false
  |--------------------------------------------------------------------------
  |
  */
  protected function anyIssetOrEmpty(array $haystack, array $needles): bool
  {
    foreach($needles as $needle)
    {
      if(!isset($haystack[$needle]) || empty($haystack[$needle]))
      {
        return true;
      }
    }

    return false;
  }

  /*
  |--------------------------------------------------------------------------
  | Check if bucket visibility type is allowed.
  |--------------------------------------------------------------------------
  |
  | BackBlaze B2 Buckets can be public or private. Any other option is invalid.
  |
  |--------------------------------------------------------------------------
  | @param  (string) $bucketType
  | @return Exception | (bool) true
  |--------------------------------------------------------------------------
  |
  */
  protected function checkBucketType(string $bucketType): mixed
  {
    $types = [Bucket::TYPE_PUBLIC, Bucket::TYPE_PRIVATE];

    if(!in_array($bucketType, $types))
    {
      throw new ValidationException(vsprintf('Valid bucket types allowed are %s or %s', $types));
    }

    return true;
  }
}
