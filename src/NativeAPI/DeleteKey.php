<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait DeleteKey
{
  public function deleteKey(array $options)
  {
    $mandatoryOptions = ['applicationKeyId'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    $options = $this->cleanArrayOfNulls([
      'applicationKeyId' => $options['applicationKeyId'],
    ], $options);

    return $this->request('POST', 'b2_delete_key', ['json' => $options]);
  }
}
