<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait UploadPart
{
  public function uploadPart(array $options = [], array $headers = [])
  {
    $mandatoryOptions = ['uploadPartUrl', 'body'];
    $mandatoryHeaders = ['Authorization', 'Content-Length', 'X-Bz-Part-Number', 'X-Bz-Content-Sha1'];

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
      'Content-Length'                                 => $headers['Content-Length'],
      'X-Bz-Part-Number'                               => $headers['X-Bz-Part-Number'],
      'X-Bz-Content-Sha1'                              => $headers['X-Bz-Content-Sha1'],
      'X-Bz-Server-Side-Encryption-Customer-Algorithm' => null,
      'X-Bz-Server-Side-Encryption-Customer-Key'       => null,
      'X-Bz-Server-Side-Encryption-Customer-Key-Md5'   => null,
    ], $headers);

    return $this->request('POST', $options['uploadPartUrl'], $options, $headers);
  }
}
