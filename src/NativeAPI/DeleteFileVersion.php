<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait DeleteFileVersion
{
  public function deleteFileVersion(array $options)
  {
    $mandatoryOptions = ['fileName', 'fileId'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    $options = $this->cleanArrayOfNulls([
      'fileName'         => $options['fileName'],
      'fileId'           => $options['fileId'],
      'bypassGovernance' => null,
    ], $options);

    return $this->request('POST', 'b2_delete_file_version', ['json' => $options]);
  }
}
