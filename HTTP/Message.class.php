<?php

namespace HTTP;

class Message {

	public bool     $request  = false;
	public String   $protocol = Message::PROTOCOL;
	
	public String   $path     = "";
	public String   $method   = "";
	
	public ?Status  $status   = null;
	
	public Array    $header   = [];
	public String   $body     = "";
	
	
	public static function createResponseStatusMessage(Status $status): Message {
	
		$message = new Message();
		$message->status = $status;
		$message->body   = Status::createStatusPage($status);
		$message->header = [
			Message::HEADER_DATE           => SocketServer::getSystemTime(),
			Message::HEADER_SERVER         => Message::SERVER_SOFTWARE,
			Message::HEADER_CONTENT_LENGTH => strlen($message->body),
			Message::HEADER_CONTENT_TYPE   => StaticResource::TYPE_HTML,
		];
		
		return $message;
	
	}

	public static function parseRequestPacket(String $packet): Message {
	
		$lines = explode("\n", $packet);
		
		$message = new Message();
		$message->status = new Status(Status::STATUS_OK);
		
		$startLine = explode(" ", trim($lines[0]));
		if (count($startLine) != 3) {
			$message->status = new Status(Status::STATUS_BAD_REQUEST);
		}
		
		$message->request   = true;
		$message->method    = strtoupper($startLine[0]) ?? "GET";
		$message->path      = trim($startLine[1]) ?? "";
		$message->protocol  = $startLine[2] ?? Message::PROTOCOL;
		
		$message->header    = [];
		
		for ($i = 1; $i < count($lines); $i++) {
		
			if (strlen($lines[$i]) < 2) {
				continue;
			}
		
			$headerName     = "";
			$headerValue    = "";
			$tmp            = "";
			$separatorFound = false;
			
			for ($j = 0; $j < strlen($lines[$i]); $j++) {
				if (ord($lines[$i][$j]) == 58 && !$separatorFound) {
					$separatorFound = true;
					$headerName = strtoupper(trim($tmp));
					$tmp = "";
					continue;
				}
				$tmp .= $lines[$i][$j];
			}
			$headerValue = trim($tmp);
			
			if (strlen($headerName) == 0 || strlen($headerValue) == 0) {
				$message->status = new Status(Status::STATUS_BAD_REQUEST);
				continue;
			}
			
			$message->header[$headerName] = $headerValue;
		
		}
		
		return $message;
	
	}
	
	
	public static function packResponseMessage(Message $message): String {
	
		$packet = "";
		
		$packet .= $message->protocol.chr(32);
		$packet .= $message->status->code.chr(32);
		$packet .= $message->status->message."\r\n";
		
		foreach ($message->header as $headerName => $headerValue) {
			$packet .= $headerName.": ".$headerValue."\r\n";
		}
		$packet .= "\r\n";
		
		if (strlen($message->body) > 1) {
			$packet .= $message->body;
		}

		return $packet;
	
	}
	
	public static function isImplementedMethod(String $method): bool {
	
		return ($method == "GET" || $method == "HEAD" || $method == "POST");
	
	}
	
	
	const PROTOCOL                  = "HTTP/1.0";
	const SERVER_SOFTWARE           = "simserver";
	
	const HEADER_ALLOW              = "Allow";
	const HEADER_AUTHORIZATION      = "Authorization";
	const HEADER_CONTENT_ENCODING   = "Content-Encoding";
	const HEADER_CONTENT_LENGTH     = "Content-Length";
	const HEADER_CONTENT_TYPE       = "Content-Type";
	const HEADER_DATE               = "Date";
	const HEADER_EXPIRES            = "Expires";
	const HEADER_FROM               = "From";
	const HEADER_IF_MODIFIED_SINCE  = "If-Modified-Since";
	const HEADER_LAST_MODIFIED      = "Last-Modified";
	const HEADER_LOCATION           = "Location";
	const HEADER_MIME_VERSION       = "MIME-version";
	const HEADER_PRAGAMA            = "Pragma";
	const HEADER_REFERER            = "Referer";
	const HEADER_SERVER             = "Server";
	const HEADER_USER_AGENT         = "User-Agent";
	const HEADER_WWW_AUTHENTICATE   = "WWW-Authenticate";

};
