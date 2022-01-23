<?php

	$_HTTPMETHOD = array(
		"GET" => 1,
		"POST" => 0,
		"PUT" => 0,
		"PATCH" => 0,
		"DELETE" => 0
	);
	
	$_HTTPSTATUS = array(
		"200" => "OK",
		"400" => "Bad Request",
		"403" => "Forbidden",
		"404" => "Not Found",
		"500" => "Internal Server Error"
	);
	
	$_CONTENTTYPE = array(
		"xml" => "text/xml",
		"htm" => "text/html",
		"html" => "text/html",
		"txt" => "text/plain",
		"css" => "text/css",
		"js" => "text/javascript",
		"pdf" => "application/pdf",
		"zip" => "application/zip",
		"jpg" => "image/jpeg",
		"jpeg" => "image/jpeg",
		"png" => "image/png"
	);
	
	// 프로토콜이 HTTP인 경우 true 반환
	function checkProtocol($protocol) {
		if (strpos($protocol, "HTTP") !== false) {
			return true;
		}
		return false;
	}
	
	// 메소드가 유효하면 true 반환
	function checkMethod($httpMethod) {
		global $_HTTPMETHOD;
		$httpMethod = strtoupper($httpMethod);
		if (array_key_exists($httpMethod, $_HTTPMETHOD) && $_HTTPMETHOD[$httpMethod]) {
			return true;
		}
		return false;
	}
	
	// 요청받은 리소스의 타입 반환
	function getResourceType($requestUri) {
		global $_CONTENTTYPE;
		if (file_exists($requestUri)) {
			$resourceInfo = pathinfo($requestUri);
			if (array_key_exists($resourceInfo["extension"], $_CONTENTTYPE)) {
				return $_CONTENTTYPE[$resourceInfo["extension"]];
			} 
		}
		return NULL;
	}
	
	// 요청받은 리소스 열어서 내용을 문자열로 반환
	function openRequestUri($requestUri, $httpStatusCode) {
		global $_CONTENTTYPE;
		global $_HTTPSTATUS;
		if ($httpStatusCode != 200) {
			$errorMessage = $httpStatusCode." ".$_HTTPSTATUS[$httpStatusCode];
			$error = "<!DOCTYPE html><head><title>".$errorMessage."</title><meta charset=\"UTF-8\"></head><h1>".$errorMessage." </h1>";
			return $error;
		}
		$resourceContents = file_get_contents($requestUri);
		return $resourceContents;
	}
	
	// 요청 헤더를 배열로 반환
	function getRequestHeader($requestHeader) {
		$lines = explode("\n", $requestHeader);
		foreach($lines as $value) {
			if (strpos($value, "HTTP/") !== false) {
				$headerContents = explode(" ", $value);
				$_HEADER["METHOD"] = $headerContents[0];
				$_HEADER["URI"] = $headerContents[1];
				$_HEADER["PROTOCOL"] = $headerContents[2];
			} else {
				$headerContents = explode(":", $value);
				if (isset($headerContents[1]) && !empty($headerContents[1])) {
					$_HEADER[$headerContents[0]] = $headerContents[1];
				}
			}
		}
		return $_HEADER;
	}
	
	// HTTP 응답 코드 설정
	function setStatusCode($_HEADER, $_SERVERCONF) {
		if (!checkProtocol($_HEADER["PROTOCOL"])) {
			return 500;
		}
		if (!checkMethod($_HEADER["METHOD"])) {
			return 400;
		}
		
		$requestUri = realpath($_SERVERCONF["DOC_ROOT"]."/".$_HEADER["URI"]);
		if ($_HEADER["URI"] == "/") {
			$requestUri = realpath($_SERVERCONF["DOC_ROOT"]."/".$_SERVERCONF["DOC_INDEX"]);
		}
		
		if (file_exists($requestUri)) {
			if (strpos(realpath($requestUri), $_SERVERCONF["DOC_ROOT"]) !== false) {
				if (is_readable($requestUri)) {
					return 200;
				} else {
					return 403;
				}
			} else {
				return 403;
			}
		} else {
			return 404;
		}
	}
	
	// HTTP 응답 헤더 설정해서 문자열로 반환
	function setResponseHeader($_HEADER, $_SERVERCONF, $httpStatusCode) {
		global $_HTTPSTATUS;
		$requestUri = realpath($_SERVERCONF["DOC_ROOT"]."/".$_HEADER["URI"]);
		if ($_HEADER["URI"] == "/") {
			$requestUri = realpath($_SERVERCONF["DOC_ROOT"]."/".$_SERVERCONF["DOC_INDEX"]);
		}
		$requestResource = openRequestUri($requestUri, $httpStatusCode);
		$resourceType = getResourceType($requestUri);
		if ($resourceType == NULL) {
			$resourceType = "text/html";
		}
		
		$responseHeader = "HTTP/1.1 ".$httpStatusCode." ".$_HTTPSTATUS[$httpStatusCode].chr(13).chr(10);
		$responseHeader .= "Server: simserver/0.2".chr(13).chr(10);
		$responseHeader .= "Accept-Ranges: bytes".chr(13).chr(10);
		$responseHeader .= "Content-Length: ".strlen($requestResource).chr(13).chr(10);
		$responseHeader .= "Content-Type: ".$resourceType.chr(13).chr(10).chr(13).chr(10);
		
		$responseHeader .= (string)$requestResource.chr(13).chr(10);
		
		return $responseHeader;
	}