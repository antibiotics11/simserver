<?php

namespace simserver\Exception;

class Exception extends \Exception {

	public function __construct(String $description, $exceptionCode = 0) {
		parent::__construct($description, $exceptionCode);
	}

};
