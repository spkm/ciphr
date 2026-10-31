<?php

namespace spkm\Ciphr\Exceptions;

use spkm\Ciphr\CiphrApiException;

/**
 * Ciphr failed (5xx or 408). Ciphr also answers 500 to a payload that fails validation: errors() then lists what was wrong.
 */
class ServerErrorException extends CiphrApiException {}
