<?php
	
	class HTTP {
		
		public $_HEADER = array();
		protected $_SERVERCONF = array();
	
		// 지원되는 메소드는 1, 지원되지 않으면 0
		protected $_HTTPMETHOD = array(
			"GET" => 1,
			"POST" => 0,
			"PUT" => 0,
			"PATCH" => 0,
			"DELETE" => 0
		);
		
		// HTTP 상태코드별 요약
		protected $_HTTPSTATUS = array(
			"200" => "OK",
			"400" => "Bad Request",
			"403" => "Forbidden",
			"404" => "Not Found",
			"500" => "Internal Server Error"
		);
		
		protected $_CONTENTTYPE = array(
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
		
		// 프로토콜이 HTTP이면 true, 아니면 false 반환
		private function checkProtocol(string $protocol): bool {
			return (strpos($protocol, "HTTP") !== false) ? true : false;
		}
		
		// 메소드가 유효하면 true, 아니면 false 반환
		private function checkMethod(string $method): bool {
			$valid = (array_key_exists(strtoupper($method), $this->_HTTPMETHOD) && $this->_HTTPMETHOD[$method]);
			return $valid ? true : false;
		}
		
		// 요청받은 리소스의 타입 반환
		private function getResourceType(string $uri): string {
			if (file_exists($uri)) {
				$resourceInfo = pathinfo($uri);
				if (array_key_exists($resourceInfo["extension"], $this->_CONTENTTYPE)) {
					return $this->_CONTENTTYPE[$resourceInfo["extension"]];
				}
			}
			return (string)"text/html";
		}
		
		// 요청받은 리소스 열어서 내용을 문자열로 반환
		private function openRequestUri(string $uri, int $httpStatusCode): string {
			if ($httpStatusCode != 200) {
				$errorMessage = $httpStatusCode.chr(32).$this->_HTTPSTATUS[$httpStatusCode];
				$error = "<!DOCTYPE html><head><title>".$errorMessage."</title><meta charset=\"UTF-8\"></head><h1>".$errorMessage." </h1>";
				return (string)$error;
			}
			$resourceContents = file_get_contents($uri);
			return (string)$resourceContents;
		}

		// 로그 추가 **에러 발생
		public function putConnectionLog(): void {
			$log = $this->_HEADER["CLIENT_NAME"].chr(32).$this->_HEADER["METHOD"].chr(32);
			$log .= $this->_HEADER["URI"].chr(32).$this->_HEADER["PROTOCOL"].chr(13).chr(10);
			$log .= $this->_HEADER["User-Agent"].chr(13).chr(10);
			$logfile = $this->_SERVERCONF["LOG_DIR"].DIRECTORY_SEPARATOR."http.log";
			if (!file_exists($logfile)) {
				file_put_contents($logfile, $log);
			} else {
				$fileopen = fopen($logfile, "a+");
				fwrite($fileopen, $log);
				fclose($fileopen);
			}
		}
		
		// 요청 헤더를 쪼개서 헤더 배열에 입력
		public function getRequestHeader(string $header): void {
			$lines = explode("\n", $header);
			foreach($lines as $value) {
				if (strpos($value, "HTTP/") !== false) {
					$contents = explode(" ", $value);
					$this->_HEADER["METHOD"] = $contents[0];
					$this->_HEADER["URI"] = $contents[1];
					$this->_HEADER["PROTOCOL"] = $contents[2];
					continue;
				}
				$contents = explode(":", $value);
				if (isset($contents[1]) && !empty($contents[1])) {
					$this->_HEADER[$contents[0]] = $contents[1];
				}
			}
		}
		
		public function __construct(array $_SERVERCONF) {
			$this->_SERVERCONF = $_SERVERCONF;
		}
		
		// 응답 코드 설정
		public function setStatusCode(): int {
			if (!$this->checkProtocol($this->_HEADER["PROTOCOL"])) return 500;
			if (!$this->checkMethod($this->_HEADER["METHOD"])) return 400;
			
			$uri = realpath($this->_SERVERCONF["DOC_ROOT"].DIRECTORY_SEPARATOR.$this->_HEADER["URI"]);
			if ($this->_HEADER["URI"] == "/")  {
				$uri = realpath($this->_SERVERCONF["DOC_ROOT"].DIRECTORY_SEPARATOR.$this->_SERVERCONF["DOC_INDEX"]);
			}
			
			if (file_exists($uri)) {
				if (strpos(realpath($uri), $this->_SERVERCONF["DOC_ROOT"]) !== false) {
					if (is_readable($uri) && is_writable($uri)) {
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
			
			return 500;
		}
		
		// HTTP 응답 헤더 설정해서 문자열로 반환
		public function setResponseHeader(int $httpStatusCode): string {
			$uri = realpath($this->_SERVERCONF["DOC_ROOT"].DIRECTORY_SEPARATOR.$this->_HEADER["URI"]);
			if ($this->_HEADER["URI"] == "/") {
				$uri = realpath($this->_SERVERCONF["DOC_ROOT"].DIRECTORY_SEPARATOR.$this->_SERVERCONF["DOC_INDEX"]);
			}
			$requestResource = $this->openRequestUri($uri, $httpStatusCode);
			$resourceType = $this->getResourceType($uri);
			
			$responseHeader = "HTTP/1.1".chr(32).$httpStatusCode.chr(32).$this->_HTTPSTATUS[$httpStatusCode].chr(13).chr(10);
			$responseHeader .= "Accept-Ranges: bytes".chr(13).chr(10);
			$responseHeader .= "Content-Length:".chr(32).strlen($requestResource).chr(13).chr(10);
			$responseHeader .= "Content-Type:".chr(32).$resourceType.chr(13).chr(10).chr(13).chr(10);
			$responseHeader .= $requestResource.chr(13).chr(10);
			
			return (string)$responseHeader;
		}
		
	};
	