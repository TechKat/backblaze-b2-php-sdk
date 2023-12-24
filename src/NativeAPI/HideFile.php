<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait HideFile
{
  public function hideFile(array $options = [])
  {
    $mandatoryOptions = ['bucketId', 'fileName'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    $options = $this->cleanArrayOfNulls([
      'bucketId' => $options['bucketId'],
      'fileName' => $options['fileName'],
    ], $options);

    return $this->request('POST', 'b2_hide_file', ['json' => $options]);
  }
}
