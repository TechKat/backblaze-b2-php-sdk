<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait UpdateFileLegalHold
{
  public function updateFileLegalHold(array $options = [])
  {
    $mandatoryOptions = ['fileName', 'fileId', 'legalHold'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    if(in_array($options['legalHold'], ['on', 'off']) === false)
    {
      throw new ValidationException('legalHold key must contain the value of either on or off.');
    }

    $options = $this->cleanArrayOfNulls([
      'fileName'  => $options['fileName'],
      'fileId'    => $options['fileId'],
      'legalHold' => $options['legalHold'],
    ], $options);

    return $this->request('POST', 'b2_update_file_legal_hold', ['json' => $options]);
  }
}
