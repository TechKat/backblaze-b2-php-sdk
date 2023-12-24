<?php

namespace TechKat\BackblazeB2\Helpers;

use TechKat\BackblazeB2\Bucket;

trait GetBucketProperties
{
  /*
  |--------------------------------------------------------------------------
  | Get Bucket's Name From Bucket ID
  |--------------------------------------------------------------------------
  |
  | Use the Bucket's unqiue ID to find out its friendly name.
  |
  |--------------------------------------------------------------------------
  | @param  (string) $bucketId
  | @return (string) | null
  |--------------------------------------------------------------------------
  |
  */
  public function getBucketNameFromId(string $bucketId): ?string
  {
    $bucket = $this->getBucketFromId($bucketId);

    if($bucket instanceof Bucket)
    {
      return $bucket->getName();
    }

    return null;
  }

  /*
  |--------------------------------------------------------------------------
  | Get Bucket's ID From Bucket Name
  |--------------------------------------------------------------------------
  |
  | Use the Bucket's friendly name to find out its unique ID.
  |
  |--------------------------------------------------------------------------
  | @param  (string) $bucketName
  | @return (string) | null
  |--------------------------------------------------------------------------
  |
  */
  public function getBucketIdFromName(string $bucketName): ?string
  {
    $bucket = $this->getBucketFromName($bucketName);

    if($bucket instanceof Bucket)
    {
      return $bucket->getId();
    }

    return null;
  }

  /*
  |--------------------------------------------------------------------------
  | Get Bucket Model from friendly name
  |--------------------------------------------------------------------------
  |
  | Show the bucket that matches friendly name.
  |
  |--------------------------------------------------------------------------
  | @param  (string) $bucketName
  | @return TechKat\BackBlazeB2\Bucket | null
  |--------------------------------------------------------------------------
  |
  */
  public function getBucketFromName(string $bucketName): ?string
  {
    $buckets = $this->listBuckets([], false, 'name');
    return $buckets[$bucketName] ?? null;
  }

  /*
  |--------------------------------------------------------------------------
  | Get Bucket Model from unique ID
  |--------------------------------------------------------------------------
  |
  | Show the bucket that matches unique ID.
  |
  |--------------------------------------------------------------------------
  | @param  (string) $bucketId
  | @return TechKat\BackBlazeB2\Bucket | null
  |--------------------------------------------------------------------------
  |
  */
  public function getBucketFromId(string $bucketId): ?string
  {
    $buckets = $this->listBuckets([], false, 'id');
    return $buckets[$bucketId] ?? null;
  }
}
