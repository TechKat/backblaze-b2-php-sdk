<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

use TechKat\BackblazeB2\Bucket;

trait ListBuckets
{
  public function listBuckets(array $options = [], bool $forceRefresh = false, string $setArrayKeyAs = 'name', $lifetime = 28800)
  {
    $cacheKey = 'Backblaze-B2-SDK-List-Buckets';
    $arrayOfBucketObjects = [];

    if($forceRefresh === true)
    {
      $this->cache->forget($cacheKey);
    }

    if(!$this->cache->has($cacheKey))
    {
      $requestOptions = $this->cleanArrayOfNulls([
        'accountId'  => $this->accountId,
        'bucketId'   => null,
        'bucketName' => null,
        'bucketType' => null,
      ], $options);

      $response = $this->request('GET', 'b2_list_buckets', ['query' => $requestOptions]);
      $this->cache->put($cacheKey, $response['buckets'], $lifetime);
    }

    $store = $this->cache->get($cacheKey);

    foreach($store as &$bucket)
    {
      $bucketKey = $setArrayKeyAs == 'name' ? $bucket['bucketName'] : $bucket['bucketId'];
      $arrayOfBucketObjects[$bucketKey] = new Bucket($bucket);
    }

    return $arrayOfBucketObjects;
  }
}
