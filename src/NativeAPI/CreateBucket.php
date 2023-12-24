<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait CreateBucket
{
  public function createBucket(array $options)
  {
    $mandatoryOptions = ['bucketName', 'bucketType'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    $this->checkBucketType($options['bucketType']);

    $requestOptions = $this->cleanArrayOfNulls([
      'accountId'                   => $this->accountId,
      'bucketName'                  => $options['bucketName'],
      'bucketType'                  => $options['bucketType'],
      'bucketInfo'                  => null,
      'corsRules'                   => null,
      'fileLockEnabled'             => null,
      'lifecycleRules'              => null,
      'replicationConfiguration'    => null,
      'defaultServerSideEncryption' => null,
    ], $options);

    $response = $this->request('POST', 'b2_create_bucket', ['json' => $requestOptions]);

    return new Bucket($response);
  }
}
