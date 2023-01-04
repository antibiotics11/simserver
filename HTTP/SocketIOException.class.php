<?php

namespace HTTP;

class SocketIOException extends \Exception {

	public int     $socketErrorCode;
	public String  $socketErrorDescription;

	public function __construct(int $socketErrorCode, int $exceptionCode = 0) {

		$this->socketErrorCode = $socketErrorCode;
		$this->socketErrorDescription = "SocketIOException: ".socket_strerror($socketErrorCode);
		
		parent::__construct($this->socketErrorDescription, $exceptionCode);
	
	}

};
