<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait GetDownloadAuthorization
{
  public function getDownloadAuthorization(array $options)
  {
    $mandatoryOptions = ['bucketId', 'fileNamePrefix', 'validDurationInSeconds'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    $options = $this->cleanArrayOfNulls([
      'bucketId'               => $options['bucketId'],
      'fileNamePrefix'         => $options['fileNamePrefix'],
      'validDurationInSeconds' => $options['validDurationInSeconds'],
      'b2ContentDisposition'   => null,
      'b2ContentLanguage'      => null,
      'b2Expires'              => null,
      'b2CacheControl'         => null,
      'b2ContentEncoding'      => null,
      'b2ContentType'          => null,
    ], $options);

    return $this->request('POST', 'b2_get_download_authorization', ['json' => $options]);
  }
}
