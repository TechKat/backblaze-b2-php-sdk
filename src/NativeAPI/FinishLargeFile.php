<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait FinishLargeFile
{
  public function finishLargeFile(array $options)
  {
    $mandatoryOptions = ['fileId', 'partSha1Array'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    $options = $this->cleanArrayOfNulls([
      'fileId'        => $options['fileId'],
      'partSha1Array' => $options['partSha1Array'],
    ], $options);

    return $this->request('POST', 'b2_finish_large_file', ['json' => $options]);
  }
}
