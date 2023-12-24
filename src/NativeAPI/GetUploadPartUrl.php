<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait GetUploadPartUrl
{
  public function getUploadPartUrl(array $options = [], bool $forceRefresh = false, int $lifetime = 28800)
  {
    $mandatoryOptions = ['fileId'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    $cacheKey = 'Backblaze-B2-SDK-Part-Upload-Url';

    $options = $this->cleanArrayOfNulls([
      'fileId' => $options['fileId'],
    ], $options);

    if($forceRefresh === true)
    {
      $this->cache->forget($cacheKey);
    }

    if($this->cache->has($cacheKey) === false)
    {
      $response = $this->request('GET', 'b2_get_upload_part_url', ['query' => $options]);
      $this->cache->put($cacheKey, $response, $lifetime);
    }

    return $this->cache->get($cacheKey);
  }
}
