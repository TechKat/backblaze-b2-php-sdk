<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait CancelLargeFile
{
  public function cancelLargeFile(array $options)
  {
    $mandatoryOptions = ['fileId'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    $options = $this->cleanArrayOfNulls([
      'fileId' => $options['fileId'],
    ], $options);

    return $this->request('POST', 'b2_cancel_large_file', ['json' => $options]);
  }
}
