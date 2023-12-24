<?php

namespace TechKat\BackblazeB2;

class Bucket
{
  protected $bucketId;
  protected $bucketName;
  protected $bucketType;
  protected $bucketInfo;
  protected $corsRules;
  protected $fileLockConfiguration;
  protected $defaultServerSideEncryption;
  protected $lifecycleRules;
  protected $revision;
  protected $options;

  const TYPE_PUBLIC = 'allPublic';
  const TYPE_PRIVATE = 'allPrivate';

  /*
  |--------------------------------------------------------------------------
  | Create Bucket Model
  |--------------------------------------------------------------------------
  |
  | Following the successful response of accessing a bucket, we will parse
  | the response body into the Bucket class to generate an easy-to-use class
  | to get the data using human-readable methods.
  |
  |--------------------------------------------------------------------------
  | @param  (array) $values
  | @return (empty)
  |--------------------------------------------------------------------------
  |
  */
  public function __construct(array $values)
  {
    foreach($values as $key => $value)
    {
      $this->{$key} = $value;
    }
  }

  /*
  |--------------------------------------------------------------------------
  | Get the bucket's unique ID
  |--------------------------------------------------------------------------
  |
  | Return the unique ID of a BackBlaze B2 bucket.
  |
  |--------------------------------------------------------------------------
  | @param  (none)
  | @return (string)
  |--------------------------------------------------------------------------
  |
  */
  public function getId()
  {
    return $this->bucketId;
  }

  /*
  |--------------------------------------------------------------------------
  | Get the bucket's friendly name
  |--------------------------------------------------------------------------
  |
  | Return the friendly name of a BackBlaze B2 bucket.
  |
  |--------------------------------------------------------------------------
  | @param  (none)
  | @return (string)
  |--------------------------------------------------------------------------
  |
  */
  public function getName()
  {
    return $this->bucketName;
  }

  /*
  |--------------------------------------------------------------------------
  | Get bucket's visibility type
  |--------------------------------------------------------------------------
  |
  | Return whether the bucket is private or public.
  |
  |--------------------------------------------------------------------------
  | @param  (none)
  | @return (string)
  |--------------------------------------------------------------------------
  |
  */
  public function getType()
  {
    return $this->bucketType;
  }

  /*
  |--------------------------------------------------------------------------
  | Get bucket's revision configuration
  |--------------------------------------------------------------------------
  |
  | Return as a JSON object containing the configuration of a bucket's revision settings.
  |
  |--------------------------------------------------------------------------
  | @param  (none)
  | @return (string)
  |--------------------------------------------------------------------------
  |
  */
  public function getRevision()
  {
    return $this->revision;
  }

  /*
  |--------------------------------------------------------------------------
  | Get bucket's CORS rules
  |--------------------------------------------------------------------------
  |
  | Return as a JSON object containing the configuration of a bucket's CORS settings.
  |
  |--------------------------------------------------------------------------
  | @param  (none)
  | @return (string)
  |--------------------------------------------------------------------------
  |
  */
  public function getCORSRules()
  {
    return $this->corsRules;
  }

  /*
  |--------------------------------------------------------------------------
  | Get bucket's lifecycle rules
  |--------------------------------------------------------------------------
  |
  | Return as a JSON object containing the configuration of a bucket's Life Cycle Rules.
  |
  |--------------------------------------------------------------------------
  | @param  (none)
  | @return (string)
  |--------------------------------------------------------------------------
  |
  */
  public function getLifeCycleRules()
  {
    return $this->lifecycleRules;
  }
}
