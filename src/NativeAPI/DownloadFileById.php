<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait DownloadFileById
{
  public function downloadFileById(array $options)
  {
    $mandatoryOptions = ['fileId'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    $options = $this->cleanArrayOfNulls([
      'fileId'               => $options['fileId'],
      'b2ContentDisposition' => null,
      'b2ContentLanguage'    => null,
      'b2Expires'            => null,
      'b2CacheControl'       => null,
      'b2ContentEncoding'    => null,
      'b2ContentType'        => null,
    ], $options);

    return $this->request('GET', 'b2_download_file_by_id', ['query' => $options]);
  }
}
