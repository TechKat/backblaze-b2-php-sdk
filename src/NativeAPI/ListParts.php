<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait ListParts
{
  public function listParts(array $options = [])
  {
    $mandatoryOptions = ['fileId'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    $options = $this->cleanArrayOfNulls([
      'fileId'          => $options['fileId'],
      'startPartNumber' => null,
      'maxPartCount'    => null,
    ], $options);

    return $this->request('GET', 'b2_list_parts', ['query' => $options]);
  }
}
