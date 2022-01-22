<?php
	
	class httpServer {
		
		private $SERVER_NAME;
		private $PORT;
		private $DOC_ROOT;
		private $DOC_INDEX;
		
		/* 지원하는 HTTP 메소드는 1, 지원하지 않으면 0 */
		private $_METHOD = array(
			"GET" => 1,
			"POST" => 0,
			"PUT" => 0,
			"PATCH" => 0,
			"DELETE" => 0
		);
		
		/* HTTP 응답 코드별 설명 */
		private $_HTTPSTATUS = array(
			"200" => "OK",
			"403" => "Forbidden",
			"404" => "Not Found",
			"500" => "Internal Server Error"
		);
		
		/* 프로토콜이 HTTP인 경우 true 반환 */
		private function checkProtocol($protocol) {
			if (strpos($protocol, "HTTP") !== false) {
				return true;
			}
			return false;
		}
		
		/* 메소드가 유효하면 true 반환 */
		private function checkMethod($method) {
			$method = strtoupper($method);
			if (array_key_exists($method, $this->_METHOD) && $this->_METHOD[$method]) {
				return true;
			}
			return false;
		}
		
		/* 요청 URI 열어서 문자열로 반환 */
		private function openRequestUri($requestUri, $httpStatusCode) {
			if ($httpStatusCode != 200) {
				return "<!DOCTYPE html><meta charset=\"UTF-8\"><h1>".$httpStatusCode." ERROR OCCURED. </h1>";
			}
			$fileContents = file_get_contents($requestUri);
			return $fileContents;
		}
		
		public function __construct($SERVER_NAME, $PORT, $DOC_ROOT, $DOC_INDEX) {
			$this->SERVER_NAME = $SERVER_NAME;
			$this->PORT = $PORT;
			$this->DOC_ROOT = $DOC_ROOT;
			$this->DOC_INDEX = $DOC_INDEX;
		} 
		
		public function getServerName() {
			return $this->SERVER_NAME;
		}
		
		public function getListeningPort() {
			return $this->PORT;
		}
		
		/* HTTP 요청 헤더를 배열로 반환 */
		public function getRequestHeader($requestHeader) {
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
		
		/* HTTP 응답 코드 설정 */
		public function setStatusCode($_HEADER) {
			if ($this->checkProtocol($_HEADER["PROTOCOL"]) && $this->checkMethod($_HEADER["METHOD"])) {
				if (trim(strtolower((string)$_HEADER["Host"])) != $this->SERVER_NAME) {
					return 403;
				}
				$requestUri = $this->DOC_ROOT.$_HEADER["URI"];
				if (!file_exists($requestUri)) {
					return 404;
				} else {
					if (is_readable($requestUri)) {
						return 200;
					} else {
						return 403;
					}
				}
			} else {
				return 500;
			}
		}
		
		
		
		/* HTTP 응답 헤더 문자열로 반환 */
		public function setResponseHeader($httpStatusCode, $_HEADER) {
			
			$requestUri = $this->DOC_ROOT.$_HEADER["URI"];
			if ($_HEADER["URI"] == "/") {
				$requestUri = $this->DOC_ROOT.$this->DOC_INDEX;
			}
			$requestResource = $this->openRequestUri($requestUri, $httpStatusCode);
			
			$responseHeader = "HTTP/1.1 ".$httpStatusCode." ".$this->_HTTPSTATUS[$httpStatusCode].chr(13).chr(10);
			$responseHeader .= "Server: simserver/0.1".chr(13).chr(10);
			$responseHeader .= "Accept-Ranges: bytes".chr(13).chr(10);
			$responseHeader .= "Content-Length: ".strlen($requestResource).chr(13).chr(10);
			$responseHeader .= "Content-Type: text/html".chr(13).chr(10).chr(13).chr(10);
			
			$responseHeader .= (string)$requestResource.chr(13).chr(10);

			return $responseHeader;
		}
		
	} 