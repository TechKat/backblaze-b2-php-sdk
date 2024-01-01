<?php

namespace TechKat\BackblazeB2;

/* Load dependencies */
use TechKat\BackblazeB2\Http\Client as HttpClient;
use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;

/* Load Native API endpoints */
use TechKat\BackblazeB2\NativeAPI\{
  CancelLargeFile, CopyFile, CopyPart, CreateBucket,
  CreateKey, DeleteBucket, DeleteFileVersion, DeleteKey,
  DownloadFileById, DownloadFileByName, FinishLargeFile, GetDownloadAuthorization,
  GetFileInfo, GetUploadPartUrl, GetUploadUrl, HideFile,
  ListBuckets, ListFileNames, ListFileVersions, ListKeys,
  ListParts, ListUnfinishedLargeFiles, StartLargeFile, UpdateBucket,
  UpdateFileLegalHold, UpdateFileRetention, UploadFile, UploadPart
};

/* Load Helpers */
use TechKat\BackblazeB2\Helpers\{GetBucketProperties, Validation};

/* Load Exceptions */
use Exception;
use TechKat\BackblazeB2\Exceptions\{AuthorizeClientException, BadNativeApiEndpointException, ValidationException};

class Client {

  /* Each trait represents a B2 API endpoint */
  use CancelLargeFile, CopyFile, CopyPart, CreateBucket;
  use CreateKey, DeleteBucket, DeleteFileVersion, DeleteKey;
  use DownloadFileById, DownloadFileByName, FinishLargeFile, GetDownloadAuthorization;
  use GetFileInfo, GetUploadPartUrl, GetUploadUrl, HideFile;
  use ListBuckets, ListFileNames, ListFileVersions, ListKeys;
  use ListParts, ListUnfinishedLargeFiles, StartLargeFile, UpdateBucket;
  use UpdateFileLegalHold, UpdateFileRetention, UploadFile, UploadPart;

  /* Helpers */
  use GetBucketProperties, Validation;

  const B2_API_BASE_URL = 'https://api.backblazeb2.com';
  const B2_API_V1 = '/b2api/v1/';
  const B2_API_V2 = '/b2api/v2/';

  protected $keyId, $applicationKey;
  protected $authTokenTimeout, $authorizationToken;
  protected $client, $version, $apiUrl;
  protected $cache, $downloadUrl, $recommendedPartSize;

  protected $loadedFromCache = true;

  /*
  |--------------------------------------------------------------------------
  | Construct Client class
  |--------------------------------------------------------------------------
  |
  | Construct the Backblaze B2 Client class
  |
  |--------------------------------------------------------------------------
  | @param  (string) $keyId | Default: (empty)
  | @param  (string) $applicationKey | Default: (empty)
  | @param  (string) $options | Default: array()
  | @return (empty) | TechKat\BackblazeB2\Exceptions\AuthorizeClientException
  |--------------------------------------------------------------------------
  |
  */
  public function __construct(string $keyId = '', string $applicationKey = '', array $options = [])
  {
    /*
     * Store the three main parameters as a varible within the class.
    */
    $this->keyId = $keyId;
    $this->applicationKey = $applicationKey;

    /*
     * Set a length of time in seconds for how long an authorizationToken
     * should stay in cache before being recycled.
    */
    $this->authTokenTimeout = isset($options['authTimeout']) ? $options['authTimeout'] : 43200;

    /*
     * Backblaze B2 contains 2 versions of their API, though by default, v2 is the best selected option.
    */
    $this->version = (isset($option['version']) && $option['version'] == 1) ? self::B2_API_V1 : self::B2_API_V2;

    /*
     * If the $keyId and/or $applicationKey is empty at runtime, throw an exception.
     * The $keyId and $applicationKey is mandatory for this package to work.
    */
    if(empty($this->keyId) || empty($this->applicationKey))
    {
      throw new AuthorizeClientException('Backblaze B2 Key ID and/or Application Key is missing.');
    }

    /*
     * If the authorizationToken needs to be recycled, you can force a re-authorization,
     * which will clear the current token in cache and create a new one.
    */
    $forceRefresh = (isset($options['forceReauthorization']) && $options['forceReauthorization'] === true) ? true : false;

    /*
     * Create the cache container that opens access to the cache.
     * Unless forceReauthorization is true, or the previous authorizationToken
     * has expired and a new one needs to be generated, return the currently
     * stored authorizationToken from cache.
     */
    */
    $this->createCacheContainer();
    $this->authorizeAccount($forceRefresh);
  }

  /*
  |--------------------------------------------------------------------------
  | Authorize Account and receive an authorizationToken
  |--------------------------------------------------------------------------
  |
  | This method will always call per runtime. It will check if an
  | authorizationToken is valid in the cache, and re-use it to minimize
  | the amount of API calls to BackBlaze B2.
  |
  | If the authorizationToken has expired, or being forcefully refreshed,
  | it will make a call to b2_authorize_account to get a new token
  | and store it to the cache for as long as defined under $this->authTokenTimeout
  |
  |--------------------------------------------------------------------------
  | @param  (bool) $forceRefresh | Default: false
  | @return (empty)
  |--------------------------------------------------------------------------
  |
  */
  public function authorizeAccount(bool $forceRefresh = false)
  {
    $cacheKey = 'Backblaze-B2-SDK-Authorization-Token';

    /*
     * If the $forceRefresh variable is true,
     * force the cache to forget it before proceeding.
    */
    if($forceRefresh === true)
    {
      $this->cache->forget($cacheKey);
    }

    /*
     * If the cache is missing the token or it has expired, have the method
     * generate a new one and store it to the cache for re-use until its
     * expiration.
    */
    if(!$this->cache->has($cacheKey))
    {
      $response = $this->request('GET', 'b2_authorize_account', [
        'auth' => [$this->keyId, $this->applicationKey],
      ]);

      /*
       * Store authorizationToken, apiUrl, downloadURL and recommendedPartSize to cache
      */
      $this->cache->put($cacheKey, [
        'authToken'           => $response['authorizationToken'],
        'apiUrl'              => $response['apiUrl'],
        'downloadUrl'         => $response['downloadUrl'],
        'recommendedPartSize' => $response['recommendedPartSize'],
      ], $this->authTokenTimeout);

      /*
       * This variable will be included in the response to let the user know if
       * the cache contained an active token.
      */
      $this->loadedFromCache = false;
    }

    /*
     * Grab the authorizationToken, apiUrl, downloadUrl and recommendedPartSize
     * from cache and load into variables for use with each B2 Native API endpoint.
    */
    $store = $this->cache->get($cacheKey);

    /*
     * Store the necessary response to variables for use in the SDK.
    */
    $this->authorizationToken   = $store['authToken'];
    $this->apiUrl               = $store['apiUrl'];
    $this->downloadUrl          = $store['downloadUrl'];
    $this->recommendedPartSize  = $store['recommendedPartSize'];
  }

  /*
  |--------------------------------------------------------------------------
  | Initialize the cache container
  |--------------------------------------------------------------------------
  |
  | Start the cache container to be able to read and write from the cache.
  |
  |--------------------------------------------------------------------------
  | @param  (none)
  | @return (empty)
  |--------------------------------------------------------------------------
  |
  */
  private function createCacheContainer(): mixed
  {
    /*
     * define the cache directory at package root
    */
    $cacheDir = __DIR__ . '/Cache';

    /*
     * Check if cache directory exists. If not, create it.
    */
    if(is_dir($cacheDir) === false)
    {
      mkdir($cacheDir, 0755);
    }

    /*
     * Initialize the container
    */
    $container = new Container();

    /*
     * Set configuration for container
    */
    $container['config'] = [
      'cache.default' => 'file',
      'cache.stores.file' => [
        'driver' => 'file',
        'path' => $cacheDir,
      ],
    ];

    /*
     * use Filesystem to store cache as files.
    */
    $container['files'] = new Filesystem;

    try
    {
      /*
       * Set the cache container to globally-accessible variable.
      */
      $cacheManager = new CacheManager($container);
      $this->cache = $cacheManager->store();
    }
    catch(Exception $e)
    {
      /*
       * If an error occurs, throw an exception explaining why.
      */
      throw new CacheException($e->getMessage());
    }
  }

  /*
  |--------------------------------------------------------------------------
  | Request Handler
  |--------------------------------------------------------------------------
  |
  | This method will handle all HTTP requests towards the BackBlaze B2 API
  |
  |--------------------------------------------------------------------------
  | @param  (string) $method | Default: GET
  | @param  (string) $uri | Default: null
  | @param  (array) $options | Default: (empty)
  | @param  (array) $headers | Default: array()
  | @param  (bool) $asJson | Default: true
  | @return (string)
  |--------------------------------------------------------------------------
  |
  */
  protected function request(string $method = 'GET', string $uri = null, array $options = [], array $headers = [], bool $asJson = true): mixed
  {
    /*
     * If Authorization header is not detected in the $headers parameter, set it using the existing authorizationToken
    */
    if(!is_null($this->authorizationToken) && !isset($headers['Authorization']))
    {
      $headers['Authorization'] = $this->authorizationToken;
    }

    /*
     * Combine both headers and options into one array.
    */
    $options = array_replace_recursive([
      'headers' => $headers,
    ], $options);

    /*
     * Set the base URI for the HttpClient handling the request.
     * If the apiUrl variable is set, use it - else use the B2_API_BASE_URL as default.
     *
     * After this, pass the baseUri variable to the HttpClient and the preferred API version.
    */
    $baseUri = isset($this->apiUrl) ? $this->apiUrl : self::B2_API_BASE_URL;
    $guzzleClient = new HttpClient(['base_uri' => $baseUri . $this->version]);

    /*
     * Retrieve the response and return as a json_encoded body or not,
     * depending on if $asJson is true or false.
    */
    $response = $guzzleClient->request($method, $uri, $options);
    return ($asJson) ? json_decode($response->getBody(), true) : $response->getBody();
  }

  /*
  |--------------------------------------------------------------------------
  | Load only traits that are called.
  |--------------------------------------------------------------------------
  |
  | For any API endpoint to be accessed, we'll call its respective trait.
  |
  |--------------------------------------------------------------------------
  | @param  (string) $method, (array) $parameters
  | @return TechKat\BackblazeB2\NativeAPI\(mixed)
  |--------------------------------------------------------------------------
  |
  */
  public function __call($method, $parameters)
  {
    /*
     * List of all available traits that are accessible.
     * Over time, as BackBlaze add more API endpoints, the list will be extended.
    */
    $traits = [
      'cancelLargeFile', 'copyFile', 'copyPart', 'createBucket',
      'createKey', 'deleteBucket', 'deleteFileVersion', 'deleteKey',
      'downloadFileById', 'downloadFileByName', 'finishLargeFile', 'getDownloadAuthorization',
      'getFileInfo', 'getUploadPartUrl', 'getUploadUrl', 'hideFile',
      'listBuckets', 'listFileNames', 'listFileVersions', 'listKeys',
      'listParts', 'listUnfinishedLargeFiles', 'startLargeFile', 'updateBucket',
      'updateFileLegalHold', 'updateFileRetention', 'uploadFile', 'uploadPart',
    ];

    /*
     * Convert method into StudlyCase format.
     * i.e uploadFile references TechKat\BackBlazeB2\NativeAPI\UploadFile
    */
    $callback = ucwords($method);

    /*
     * If the trait exists, call it and pass the parameters through.
    */
    if(in_array($method, $traits) && is_callable($method))
    {
      return $this->$method(...array_values($parameters));
    }

    /*
     * By default, an exception will be thrown if the trait does not exist.
    */
    throw new BadNativeApiEndpointException(sprintf('Method %s::%s does not exist.', static::class, $method));
  }
}
