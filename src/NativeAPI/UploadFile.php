<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

use TechKat\BackblazeB2\File;

trait UploadFile
{
  public function uploadFile(string $uploadUrl = '', array $options = [], array $headers = [])
  {
    $mandatoryOptions = ['uploadUrl', 'body'];
    $mandatoryHeaders = ['Authorization', 'X-Bz-File-Name', 'X-Bz-Content-Sha1', 'Content-Length', 'Content-Type'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    if($this->anyIssetOrEmpty($headers, $mandatoryHeaders))
    {
      throw new ValidationException('The following headers are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryHeaders));
    }

    $headers = $this->cleanArrayOfNulls([
      'Authorization'                                  => $headers['Authorization'],
      'Content-Type'                                   => $headers['Content-Type'],
      'Content-Length'                                 => $headers['Content-Length'],
      'X-Bz-File-Name'                                 => $headers['X-Bz-File-Name'],
      'X-Bz-Content-Sha1'                              => $headers['X-Bz-Content-Sha1'],
      'X-Bz-Info-src_last_modified_millis'             => null,
      'X-Bz-Info-b2-content-disposition'               => null,
      'X-Bz-Info-b2-content-language'                  => null,
      'X-Bz-Info-b2-expires'                           => null,
      'X-Bz-Info-b2-cache-control'                     => null,
      'X-Bz-Info-b2-content-encoding'                  => null,
      'X-Bz-Custom-Upload-Timestamp'                   => null,
      'X-Bz-File-Legal-Hold'                           => null,
      'X-Bz-File-Retention-Mode'                       => null,
      'X-Bz-File-Retention-Retain-Until-Timestamp'     => null,
      'X-Bz-Server-Side-Encryption'                    => null,
      'X-Bz-Server-Side-Encryption-Customer-Algorithm' => null,
      'X-Bz-Server-Side-Encryption-Customer-Key'       => null,
      'X-Bz-Server-Side-Encryption-Customer-Key-Md5'   => null,
    ], $headers);

    $response = $this->request('POST', $uploadUrl, $options, $headers);
    return new File($response);
  }
}
