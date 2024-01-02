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
  const TYPE_SNAPSHOT = 'snapshot';

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
  | Get the bucket's account ID
  |--------------------------------------------------------------------------
  |
  | Returns the account ID (owner) of the bucket.
  |
  |--------------------------------------------------------------------------
  | @param  (none)
  | @return (string)
  |--------------------------------------------------------------------------
  |
  */
  public function getAccountId(): string
  {
    return $this->accountId;
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
  public function getId(): string
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
  public function getName(): string
  {
    return $this->bucketName;
  }

  /*
  |--------------------------------------------------------------------------
  | Get bucket's type
  |--------------------------------------------------------------------------
  |
  | Return whether the bucket is private, public or snapshot.
  |
  |--------------------------------------------------------------------------
  | @param  (none)
  | @return (string)
  |--------------------------------------------------------------------------
  |
  */
  public function getType(): string
  {
    return $this->bucketType;
  }

  /*
  |--------------------------------------------------------------------------
  | Get bucket's revision number
  |--------------------------------------------------------------------------
  |
  | Return the revision number from bucket revisions.
  |
  |--------------------------------------------------------------------------
  | @param  (none)
  | @return (int)
  |--------------------------------------------------------------------------
  |
  */
  public function getRevision(): int
  {
    return $this->revision;
  }

  /*
  |--------------------------------------------------------------------------
  | Get bucket's CORS rules
  |--------------------------------------------------------------------------
  |
  | Return as an array containing the configuration of a bucket's CORS settings.
  |
  |--------------------------------------------------------------------------
  | @param  (none)
  | @return (array)
  |--------------------------------------------------------------------------
  |
  */
  public function getCORSRules(): array
  {
    return $this->corsRules;
  }

  /*
  |--------------------------------------------------------------------------
  | Get bucket's server-side encryption
  |--------------------------------------------------------------------------
  |
  | Return as an array containing the configuration of a bucket's server-side encryption settings.
  |
  |--------------------------------------------------------------------------
  | @param  (none)
  | @return (array)
  |--------------------------------------------------------------------------
  |
  */
  public function getServerSideEncryption(): array
  {
    return $this->defaultServerSideEncryption;
  }

  /*
  |--------------------------------------------------------------------------
  | Get bucket's file lock configuration
  |--------------------------------------------------------------------------
  |
  | Return as an array containing the configuration of a bucket's file-lock settings.
  |
  |--------------------------------------------------------------------------
  | @param  (none)
  | @return (array)
  |--------------------------------------------------------------------------
  |
  */
  public function getFileLock(): array
  {
    return $this->fileLockConfiguration;
  }

  /*
  |--------------------------------------------------------------------------
  | Get bucket's replication configuration
  |--------------------------------------------------------------------------
  |
  | Return as an array containing the configuration of a bucket's replication settings.
  |
  |--------------------------------------------------------------------------
  | @param  (none)
  | @return (array)
  |--------------------------------------------------------------------------
  |
  */
  public function getReplicationConfig(): array
  {
    return $this->replicationConfiguration;
  }

  /*
  |--------------------------------------------------------------------------
  | Get bucket's lifecycle rules
  |--------------------------------------------------------------------------
  |
  | Return as an array containing the configuration of a bucket's Life Cycle Rules.
  |
  |--------------------------------------------------------------------------
  | @param  (none)
  | @return (array)
  |--------------------------------------------------------------------------
  |
  */
  public function getLifeCycleRules(): array
  {
    return $this->lifecycleRules;
  }
}
