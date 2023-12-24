<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

use TechKat\BackblazeB2\Bucket;

trait UpdateBucket
{
  public function updateBucket(array $options = [])
  {
    $this->checkBucketType($options['bucketType']);

    $this->listBuckets([], true);

    $options = $this->cleanArrayOfNulls([
      'accountId'                   => $this->accountId,
      'bucketId'                    => $options['bucketId'] ?? $this->getBucketIdFromName($options['bucketName']),
      'bucketType'                  => $options['bucketType'],
      'bucketInfo'                  => null,
      'corsRules'                   => null,
      'defaultRetention'            => null,
      'defaultServerSideEncryption' => null,
      'fileLockEnabled'             => null,
      'lifecycleRules'              => null,
      'replicationConfiguration'    => null,
      'ifRevisionIs'                => null,
    ], $options);

    $response = $this->request('POST', 'b2_update_bucket', ['json' => $requestOptions]);
    return new Bucket($response);
  }
}
