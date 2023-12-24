<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait StartLargeFile
{
  public function startLargeFile(array $options = [])
  {
    $mandatoryOptions = ['bucketId', 'fileName'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    $options = $this->cleanArrayOfNulls([
      'bucketId'              => $options['bucketId'],
      'fileName'              => $options['fileName'],
      'contentType'           => $options['contentType'] ?? 'b2/x-auto',
      'customUploadTimestamp' => null,
      'fileInfo'              => null,
      'fileRetention'         => null,
      'legalHold'             => null,
      'serverSideEncryption'  => null,
    ], $options);

    return $this->request('POST', 'b2_start_large_file', ['json' => $options]);
  }
}
