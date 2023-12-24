<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait DeleteBucket
{
  public function deleteBucket(array $options)
  {
    $mandatoryOptions = ['bucketId'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    $options = $this->cleanArrayOfNulls([
      'accountId' => $this->accountId,
      'bucketId'  => $options['bucketId'],
    ], $options);

    $response = $this->request('POST', 'b2_delete_bucket', ['json' => $options]);

    $this->listBuckets([], true);

    return $response;
  }
}
