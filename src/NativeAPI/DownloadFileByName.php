<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait DownloadFileByName
{
  public function downloadFileByName(array $options)
  {
    $options = $this->cleanArrayOfNulls([
      'Authorization'        => null,
      'b2ContentDisposition' => null,
      'b2ContentLanguage'    => null,
      'b2Expires'            => null,
      'b2CacheControl'       => null,
      'b2ContentEncoding'    => null,
      'b2ContentType'        => null,
      'serverSideEncryption' => null,
    ], $options);

    return $this->request('GET', 'b2_download_file_by_name', ['query' => $options]);
  }
}
