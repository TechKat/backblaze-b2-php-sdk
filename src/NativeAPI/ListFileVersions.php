<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait ListFileVersions
{
  public function listFileVersions(array $options = [])
  {
    $mandatoryOptions = ['bucketId'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    $options = $this->cleanArrayOfNulls([
      'bucketId'      => $options['bucketId'],
      'startFileName' => null,
      'startFileId'   => null,
      'maxFileCount'  => null,
      'prefix'        => null,
      'delimiter'     => null,
    ], $options);

    return $this->request('GET', 'b2_list_file_versions', ['query' => $options]);
  }
}
