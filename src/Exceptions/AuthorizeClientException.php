<?php

namespace TechKat\BackblazeB2\Exceptions;

use Exception;

/*
|--------------------------------------------------------------------------
| AuthorizeClientException
|--------------------------------------------------------------------------
|
| This exception should only be used when failing to
| authenticate with b2_authorize_account
|
*/
class AuthorizeClientException extends Exception {}
