<?php

namespace spkm\Ciphr\Exceptions;

use spkm\Ciphr\CiphrApiException;

/**
 * The API key is missing, wrong, or not allowed the entity or property (401, 403).
 */
class UnauthorizedException extends CiphrApiException {}
