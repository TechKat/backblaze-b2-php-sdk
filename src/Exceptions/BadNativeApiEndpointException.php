<?php

namespace TechKat\BackblazeB2\Exceptions;

use Exception;

/*
|--------------------------------------------------------------------------
| BadNativeApiEndpointException
|--------------------------------------------------------------------------
|
| This exception is used if a BackBlaze B2 SDK trait is not available.
|
*/
class BadNativeApiEndpointException extends Exception {}
