<?php

namespace HTTP;

class Response {

	private ?Request $request       = null;	
	private ?Message $message       = null;
	private Array    $serverParams  = [];
	
	
	public function __construct(?Request $request = null, Array $serverParams = []) {

		if ($request === null) {
			return;
		}
		$this->setRequest($request, $serverParams);
	
	}
	
	public function setRequest(Request $request, Array $serverParams = []): void {
	
		$this->request      = $request;
		$this->serverParams = $serverParams;
		
		$requestMessage = $this->request->getMessage();
		$requestStatus  = $requestMessage->status;
		
		if ($requestStatus->code != Status::STATUS_OK) {
			$this->message = Message::createResponseStatusMessage($requestStatus);
			return;
		}
		
		$requestMethod = trim(strtoupper($requestMessage->method));
		
		if (!Message::isImplementedMethod($requestMethod)) {
			$this->message = Message::createResponseStatusMessage(
				new Status(Status::STATUS_NOT_IMPLEMENTED)
			);
			return;
		}
		
		$this->message = Response::$requestMethod($requestMessage, $this->serverParams);
	
	}
	
	public function getRequest(): ?Request {
	
		return $this->request;
	
	}
	
	public function setMessage(Message $message): void {
	
		$this->message = $message;
		
	}
	
	public function getMessage(): Message {
	
		return $this->message;
	
	}
	
	
	public static function GET(Message $requestMessage, Array $serverParams = []): Message {
	
		$responseMessage = new Message();
		$responseMessage->header = [
			Message::HEADER_DATE    => SocketServer::getSystemTime(),
			Message::HEADER_SERVER  => Message::SERVER_SOFTWARE,
		];
		$responseMessage->status = new Status(Status::STATUS_ACCEPTED);
		
		$path = $serverParams["document_root"]."/".$requestMessage->path;
		$path = realpath($path);
		
		if ($path === false) {
			$responseMessage->status = new Status(Status::STATUS_NOT_FOUND);
		} else {
			if (StaticResource::resourceExists($path)) {
				if (StaticResource::resourceAccessible($path)) {
					$responseMessage->status = new Status(Status::STATUS_OK);
				} else {
					$responseMessage->status = new Status(Status::STATUS_FORBIDDEN);
				}
			} else {
				$responseMessage->status = new Status(Status::STATUS_NOT_FOUND);
			}
		}
		
		if ($responseMessage->status->code == Status::STATUS_OK) {
		
			$resource = new StaticResource($path);
			
			$responseMessage->body = $resource->getResource();
			$responseMessage->header[Message::HEADER_LAST_MODIFIED] = 
				StaticResource::getResourceLastModifiedTime($resource->getPath());
			$responseMessage->header[Message::HEADER_CONTENT_TYPE] = $resource->getType();
			
		} else {
			$responseMessage->body = Status::createStatusPage($responseMessage->status);
			$responseMessage->header[Message::HEADER_CONTENT_TYPE] = StaticResource::TYPE_HTML;
		}
		$responseMessage->header[Message::HEADER_CONTENT_LENGTH] = strlen($responseMessage->body);
		
		return $responseMessage;
	
	}
	
	public static function HEAD(Message $requestMessage, Array $serverParams = []): Message {
	
		$responseMessage = Response::GET($requestMessage, $serverParams);
		$responseMessage->body = "";
		
		return $responseMessage;
	
	}

	public static function POST(Message $requestMessage, Array $serverParams = []): Message {
	
		$responseMessage = new Message();
		
		$requestPath = $requestMessage->path;
		
		
		
		return $responseMessage;
		
	}

};
