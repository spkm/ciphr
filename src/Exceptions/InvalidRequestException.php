<?php

namespace spkm\Ciphr\Exceptions;

use spkm\Ciphr\CiphrApiException;

/**
 * Ciphr rejected the request itself (400).
 */
class InvalidRequestException extends CiphrApiException {}
