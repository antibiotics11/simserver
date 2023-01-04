<?php

namespace HTTP;

class Response {

	private Request $request;
	
	private Message $message;
	
	
	public function __construct(Request $request) {

		if ($request === null) {
			return;
		}

		$this->request = $request;
		
		if ($this->request->getMessage()->status->code != Status::STATUS_OK) {
			$this->message = Message::createResponseStatusMessage(
				$this->request->getMessage()->status
			);
			return;
		}
		
	
	}
	
	
	public function setMessage(Message $message): void {
	
		$this->message = $message;
		
	}
	
	public function getMessage(): Message {
	
		return $this->message;
	
	}
	

	public function setHeader(Array $header = []): void {
	
		$this->message->header = $header;
	
	}
	
	public function getHeader(): Array {
	
		return $this->message->header;
	
	}
	
	public function setBody(String $body = ""): void {
	
		$this->message->body = $body;
	
	}
	
	public function getBody(): String {
	
		return $this->message->body;
	
	}

};
