<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait CreateKey
{
  public function createKey(array $options)
  {
    $mandatoryOptions = ['capabilities', 'keyName'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    $options = $this->cleanArrayOfNulls([
      'accountId'              => $this->accountId,
      'capabilities'           => $options['capabilities'],
      'keyName'                => $options['keyName'],
      'validDurationInSeconds' => null,
      'bucketId'               => null,
      'namePrefix'             => null,
    ], $options);

    return $this->request('POST', 'b2_create_key', ['json' => $options]);
  }
}
