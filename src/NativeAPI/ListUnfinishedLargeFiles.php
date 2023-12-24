<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait ListUnfinishedLargeFiles
{
  public function listUnfinishedLargeFiles(array $options = [])
  {
    $mandatoryOptions = ['bucketId', 'namePrefix'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    $options = $this->cleanArrayOfNulls([
      'bucketId'     => $options['bucketId'],
      'namePrefix'   => $options['namePrefix'],
      'startFileId'  => null,
      'maxFileCount' => null,
    ], $options);

    return $this->request('GET', 'b2_list_unfinished_large_files', ['query' => $options]);
  }
}
