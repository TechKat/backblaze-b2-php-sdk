<?php

namespace TechKat\BackblazeB2\NativeAPI;

use TechKat\BackblazeB2\Exceptions\ValidationException;

trait ListKeys
{
  public function listKeys(array $options = [])
  {
    $options = $this->cleanArrayOfNulls([
      'accountId'             => $this->accountId,
      'maxKeyCount'           => null,
      'startApplicationKeyId' => null,
    ], $options);

    return $this->request('GET', 'b2_list_keys', ['query' => $options]);
  }
}
