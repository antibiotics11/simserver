<?php

namespace HTTP;

class Status {

	public int     $code    = Status::STATUS_OK;
	public String  $message = "OK";
	public String  $details = "";
	
	public function __construct(int $code, String $details = "") {
	
		$this->code = $code;
		$this->message = Status::codeToMessage($code);
		$this->details = $details;
	
	}
	

	public static function createStatusPage(Status $httpStatus, String $css = ""): String {
	
		if (strlen($css) < 2) {
			$css = "
			body { margin: 0 0 0 0; padding: 0 0 0 0; background-color: #505061; }
			#container { margin-top: 4em; text-align: center; font-family: consolas, sans-serif; }
			#status-code { display: block; font-size: 10em; color: white; }
			#status-message { display: block; font-size: 3em; color: white; }
			";
		}
		
		return preg_replace("/\t/", "", "
		
			<!DOCTYPE html>
			<html lang = \"en-US\">
			<head>
				<meta http-equiv = \"content-type\" content = \"text/html\">
				<meta charset = \"UTF-8\">
				<title>".$httpStatus->message."</title>
				<style type = \"text/css\">".$css."</style>
			</head>
			<body>
				<div id = \"container\">
				<h1 id = \"status-code\">".$httpStatus->code."</h1>
				<h2 id = \"status-message\">".$httpStatus->message."</h2>
				<div id = \"status-details\">".$httpStatus->details."</div>
				</div>
			</body>
		
		");
	
	}
	
	public static function codeToMessage(int $code): String {
		
		$message = "";
		
		switch ($code) {
		
			case Status::STATUS_OK                     : $message = "OK"; break;
			case Status::STATUS_CREATED                : $message = "Created"; break;
			case Status::STATUS_ACCEPTED               : $message = "Accepted"; break;
			case Status::STATUS_NO_CONTENT             : $message = "No Content"; break;
			case Status::STATUS_MOVED_PERMANENTLY      : $message = "Moved Permanently"; break;
			case Status::STATUS_MOVED_TEMPORARILY      : $message = "Moved Temporarily"; break;
			case Status::STATUS_NOT_MODIFIED           : $message = "Not Modified"; break;
			case Status::STATUS_BAD_REQUEST            : $message = "Bad Request"; break;
			case Status::STATUS_UNAUTHORIZED           : $message = "Unauthorized"; break;
			case Status::STATUS_FORBIDDEN              : $message = "Forbidden"; break;
			case Status::STATUS_NOT_FOUND              : $message = "Not Found"; break;
			case Status::STATUS_INTERNAL_SERVER_ERROR  : $message = "Internal Server Error"; break;
			case Status::STATUS_NOT_IMPLEMENTED        : $message = "Not Implemented"; break;
			case Status::STATUS_SERVICE_UNAVAILABLE    : $message = "Service Unavailable"; break;
		};
		
		return $message;
	
	}
	
	
	const STATUS_OK                         = 200;
	const STATUS_CREATED                    = 201;
	const STATUS_ACCEPTED                   = 202;
	const STATUS_NO_CONTENT                 = 204;
	
	const STATUS_MOVED_PERMANENTLY          = 301;
	const STATUS_MOVED_TEMPORARILY          = 302;
	const STATUS_NOT_MODIFIED               = 304;
	
	const STATUS_BAD_REQUEST                = 400;
	const STATUS_UNAUTHORIZED               = 401;
	const STATUS_FORBIDDEN                  = 403;
	const STATUS_NOT_FOUND                  = 404;
	
	const STATUS_INTERNAL_SERVER_ERROR      = 500;
	const STATUS_NOT_IMPLEMENTED            = 501;
	const STATUS_SERVICE_UNAVAILABLE        = 503;


};
