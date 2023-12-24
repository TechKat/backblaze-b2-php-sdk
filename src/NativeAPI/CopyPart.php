<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait CopyPart
{
  public function copyPart(array $options)
  {
    $mandatoryOptions = ['sourceFileId', 'largeFileId', 'partNumber'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    $options = $this->cleanArrayOfNulls([
      'sourceFileId'                    => $options['sourceFileId'],
      'largeFileId'                     => $options['largeFileId'],
      'partNumber'                      => $options['partNumber'],
      'range'                           => null,
      'sourceServerSideEncryption'      => null,
      'destinationServerSideEncryption' => null,
    ], $options);

    return $this->request('POST', 'b2_copy_part', ['json' => $options]);
  }
}
