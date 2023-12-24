<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait CopyFile
{
  public function copyFile(array $options)
  {
    $mandatoryOptions = ['sourceFileId', 'fileName'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    $options = $this->cleanArrayOfNulls([
      'sourceFileId'                    => $options['sourceFileId'],
      'destinationBucketId'             => null,
      'fileName'                        => $options['fileName'],
      'range'                           => null,
      'metadataDirective'               => null,
      'contentType'                     => null,
      'fileInfo'                        => null,
      'fileRetention'                   => null,
      'legalHold'                       => null,
      'sourceServerSideEncryption'      => null,
      'destinationServerSideEncryption' => null,
    ], $options);

    return $this->request('POST', 'b2_copy_file', ['json' => $options]);
  }
}
