<?php

namespace HTTP;

class Request {

	private String   $packet  = "";
	
	private ?Message $message = null;
	
	public function __construct(String $requestPacket = "") {
	
		$this->packet = $requestPacket;
	
		if (strlen($requestPacket) > 1) {
			$this->message = Message::parseRequestPacket($requestPacket);
		}
	
	}
	
	public function getMessage(): ?Message {
		
		return $this->message;
		
	}
	
	public function getProtocol(): String {
	
		return $this->message->protocol;
	
	}
	
	public function getPath(): String {
	
		return $this->message->path;
	
	}
	
	public function getMethod(): Method {
	
		return $this->message->method;
	
	}
	
	public function getHeader(): Array {
	
		return $this->message->header;
	
	}
	
	public function getAuthorization(): String {
	
		return $this->message->header["AUTHORIZATION"] ?? "";
	
	}
	
	public function getFrom(): String {
	
		return $this->message->header["FROM"] ?? "";
	
	}
	
	public function getIfModifiedSince(): String {
	
		return $this->message->header["IF-MODIFIED-SINCE"] ?? "";
	
	}
	
	public function getReferer(): String {
	
		return $this->message->header["REFERER"] ?? "";
		
	}
	
	public function getUserAgent(): String {
	
		return $this->message->header["USER-AGENT"] ?? "";
	
	}

};
