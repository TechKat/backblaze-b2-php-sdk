<?php

namespace TechKat\BackblazeB2;

// Load dependency packaes
use Carbon\Carbon;

class File
{
  protected $accountId, $action, $bucketId;
  protected $contentLength, $contentMd5, $contentSha1;
  protected $contentType, $fileId, $fileInfo;
  protected $fileName, $fileRetention, $legalHold;
  protected $serverSideEncryption, $uploadTimestamp;

  /*
  |--------------------------------------------------------------------------
  | Create File Model
  |--------------------------------------------------------------------------
  |
  | Once the response of a successful upload, or retrieval of a file from a bucket is received,
  | we can inject the commonly known keys and values to create human-readable methods to create
  | human-readable filezies or timestamps, return as an array or JSON-encoded string.
  |
  |--------------------------------------------------------------------------
  | @param  (array) $values
  | @return (empty)
  |--------------------------------------------------------------------------
  |
  */
  public function __construct(array $values)
  {
    foreach($values as $key => $value)
    {
      $this->{$key} = $value;
    }
  }

  /*
  |--------------------------------------------------------------------------
  | Get Account ID
  |--------------------------------------------------------------------------
  |
  | Return the account ID that is associated with the file in bucket.
  |
  |--------------------------------------------------------------------------
  | @param  none
  | @return string
  |--------------------------------------------------------------------------
  |
  */
  public function getAccountId(): string
  {
    return $this->accountId;
  }

  /*
  |--------------------------------------------------------------------------
  | Get File Action
  |--------------------------------------------------------------------------
  |
  | Returns the action of the file model, most commonly it will result in
  | returning the value of upload.
  |
  |--------------------------------------------------------------------------
  | @param  none
  | @return string
  |--------------------------------------------------------------------------
  |
  */
  public function getAction(): string
  {
    return $this->action;
  }

  /*
  |--------------------------------------------------------------------------
  | Get Bucket ID
  |--------------------------------------------------------------------------
  |
  | Return the unique ID of the bucket where file is stored.
  |
  |--------------------------------------------------------------------------
  | @param  none
  | @return string
  |--------------------------------------------------------------------------
  |
  */
  public function getBucketId(): string
  {
    return $this->bucketId;
  }

  /*
  |--------------------------------------------------------------------------
  | Get Size
  |--------------------------------------------------------------------------
  |
  | Return the numeric value of the filesize in bytes.
  |
  |--------------------------------------------------------------------------
  | @param  none
  | @return int
  |--------------------------------------------------------------------------
  |
  */
  public function getSize(): int
  {
    return $this->contentLength;
  }

  /*
  |--------------------------------------------------------------------------
  | Get Human Readable Size
  |--------------------------------------------------------------------------
  |
  | Returns the value of the filesize in a human-readable format,
  | by taking the getSize() method's value to convert into a unit abbreviation.
  |
  |--------------------------------------------------------------------------
  | @param  none
  | @return string
  |--------------------------------------------------------------------------
  |
  */
  public function getHumanReadableSize(): string
  {
    $i = floor(log($this->getSize(), 1024));
    return round($this->contentLength / pow(1024, $i), [0, 0, 2, 2, 3][$i]) . ['B', 'kB', 'MB', 'GB', 'TB'][$i];
  }

  /*
  |--------------------------------------------------------------------------
  | Get MD5
  |--------------------------------------------------------------------------
  |
  | Return the MD5 hash of the file as a string.
  |
  |--------------------------------------------------------------------------
  | @param  none
  | @return string
  |--------------------------------------------------------------------------
  |
  */
  public function getMd5(): string
  {
    return $this->contentMd5;
  }

  /*
  |--------------------------------------------------------------------------
  | Get SHA1
  |--------------------------------------------------------------------------
  |
  | Return the SHA1 hash of the file as a string.
  |
  |--------------------------------------------------------------------------
  | @param  none
  | @return string
  |--------------------------------------------------------------------------
  |
  */
  public function getSha1(): string
  {
    return $this->contentSha1;
  }

  /*
  |--------------------------------------------------------------------------
  | Get Mime Type
  |--------------------------------------------------------------------------
  |
  | Returns the mimetype associated with the file as a string, whether manually
  | inputted or guessed using b2/x-auto.
  |
  |--------------------------------------------------------------------------
  | @param  none
  | @return string
  |--------------------------------------------------------------------------
  |
  */
  public function getMimeType(): string
  {
    return $this->contentType;
  }

  /*
  |--------------------------------------------------------------------------
  | Get File ID
  |--------------------------------------------------------------------------
  |
  | Each file on Backblaze B2 is associated with a unique file ID.
  | This method returns the string of the file's unique file ID.
  |
  |--------------------------------------------------------------------------
  | @param  none
  | @return string
  |--------------------------------------------------------------------------
  |
  */
  public function getFileId(): string
  {
    return $this->fileId;
  }

  /*
  |--------------------------------------------------------------------------
  | Get File Info
  |--------------------------------------------------------------------------
  |
  | If custom attributes were inserted during the upload of a file, it will be
  | returned on this method as an array.
  |
  |--------------------------------------------------------------------------
  | @param  none
  | @return array
  |--------------------------------------------------------------------------
  |
  */
  public function getFileInfo(): array
  {
    return $this->fileInfo;
  }

  /*
  |--------------------------------------------------------------------------
  | Get File Name
  |--------------------------------------------------------------------------
  |
  | Returns a string of the file name given to a file on upload.
  |
  |--------------------------------------------------------------------------
  | @param  none
  | @return string
  |--------------------------------------------------------------------------
  |
  */
  public function getFileName(): string
  {
    return $this->fileName;
  }

  /*
  |--------------------------------------------------------------------------
  | Get File Retention
  |--------------------------------------------------------------------------
  |
  | If the file contains object lock retention settings, it will be
  | included in this model as an array.
  |
  |--------------------------------------------------------------------------
  | @param  none
  | @return array
  |--------------------------------------------------------------------------
  |
  */
  public function getFileRetention(): array
  {
    return $this->fileRetention;
  }

  /*
  |--------------------------------------------------------------------------
  | Get Legal Hold
  |--------------------------------------------------------------------------
  |
  | Returns an Object Lock legal hold status for the file, if it exists.
  |
  |--------------------------------------------------------------------------
  | @param  none
  | @return array
  |--------------------------------------------------------------------------
  |
  */
  public function getLegalHold(): array
  {
    return $this->legalHold;
  }

  /*
  |--------------------------------------------------------------------------
  | Get Server Side Encryption
  |--------------------------------------------------------------------------
  |
  | If the file is encrypted with Server-Side Encryption, this method will
  | contain the mode (SSE-B2 or SSE-C) and algorithm used to encrypt the data.
  |
  |--------------------------------------------------------------------------
  | @param  none
  | @return array|null
  |--------------------------------------------------------------------------
  |
  */
  public function getServerSideEncryption(): array
  {
    return $this->serverSideEncryption;
  }

  /*
  |--------------------------------------------------------------------------
  | Get Millisecond Epoch Timestamp
  |--------------------------------------------------------------------------
  |
  | Returns the millisecond epoch timestamp that BackBlaze B2 API recorded
  | the upload completing at.
  |
  |--------------------------------------------------------------------------
  | @param  none
  | @return int
  |--------------------------------------------------------------------------
  |
  */
  public function getUploadTimestamp(): int
  {
    return $this->uploadTimestamp;
  }

  /*
  |--------------------------------------------------------------------------
  | Get Human Readable Timestamp
  |--------------------------------------------------------------------------
  |
  | Converts the millisecond epoch timestamp into a human-readable format
  | of your choice. Simply define the DateTime::format.
  |
  | Format options are available at https://www.php.net/manual/en/datetime.format.php
  |
  |--------------------------------------------------------------------------
  | @param  (string) $format | Default: Y-m-d H:i:s
  | @return string
  |--------------------------------------------------------------------------
  |
  */
  public function getHumanReadableTimestamp($format = 'Y-m-d H:i:s'): string
  {
    return Carbon::createFromTimestampMs($this->uploadTimestamp)->format($format);
  }

  /*
  |--------------------------------------------------------------------------
  | Return as an array
  |--------------------------------------------------------------------------
  |
  | Returns the File model's keys and values as a PHP associative array.
  |
  |--------------------------------------------------------------------------
  | @param  none
  | @return array
  |--------------------------------------------------------------------------
  |
  */
  public function toArray(): array
  {
    return get_object_vars($this);
  }

  /*
  |--------------------------------------------------------------------------
  | Return as a JSON array
  |--------------------------------------------------------------------------
  |
  | Returns the File model's keys and values as a JSON-encoded array.
  |
  |--------------------------------------------------------------------------
  | @param  none
  | @return string
  |--------------------------------------------------------------------------
  |
  */
  public function toJson(): string
  {
    return json_encode($this->toArray());
  }
}
