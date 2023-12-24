<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait GetUploadUrl
{
  public function getUploadUrl(array $options = [], bool $forceRefresh = false, int $lifetime = 86400)
  {
    $mandatoryOptions = ['bucketId'];

    if($this->anyIssetOrEmpty($options, $mandatoryOptions))
    {
      throw new ValidationException('The following options are mandatory for ' . __FUNCTION__ . ' method: ' . implode(', ', $mandatoryOptions));
    }

    $cacheKey = 'Backblaze-B2-SDK-Upload-Url-From-Bucket-Id';

    $options = $this->cleanArrayOfNulls([
      'bucketId' => $options['bucketId'],
    ], $options);

    if($forceRefresh === true)
    {
      $this->cache->forget($cacheKey);
    }

    if($this->cache->has($cacheKey) === false)
    {
      $response = $this->request('GET', 'b2_get_upload_url', ['query' => $options]);
      $this->cache->put($cacheKey, $response, $lifetime);
    }

    return $this->cache->get($cacheKey);
  }
}
