<?php

namespace simserver\Exception;

class ParseException extends \Exception {

  public function __construct(String $description, $exceptionCode = 0) {

		$description = sprintf("Parse Exception: %s", $description);
		parent::__construct($description, $exceptionCode);

	}

};
