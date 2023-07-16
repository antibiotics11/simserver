<?php

namespace simserver\Exception;

class ServerException extends \Exception {

	public function __construct(String $description, int $exceptionCode = 0) {
		parent::__construct($description, $exceptionCode);
	}

};
