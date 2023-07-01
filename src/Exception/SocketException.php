<?php

namespace simserver\Exception;

class SocketException extends Exception {

	public function __construct(String $description, $exceptionCode = 0) {
		parent::__construct(sprintf("Socket Exception: %s", $description), $exceptionCode);
	}

};
