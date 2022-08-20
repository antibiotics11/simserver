<?php

namespace HTTP;

class Response implements HTTP {

	private $Request = null;                                     // \HTTP\Request (Object)

	private $request_uri_absolute_path = "";                     // Server-side absolute path of requested uri (String)

	private $document_root_dir = "";                             // Document root directory (String)

	private $document_index_files = Array();                     // Document index files (Array)

	private $_STATUS_CODE = "";                                  // Response status code (String)

	private $_HEADER = array(                                    // Response header (Array)

		"Accept-Ranges"             => "bytes",
		"Content-Encoding"          => "UTF-8",
		"Vary"                      => "Accept-Encoding",
		"Server"                    => "simserver/PHP".PHP_VERSION." (".PHP_OS.")",
		"Cache-Control"             => "no-cache",
		"Pragma"                    => "no-cache",
		"Referrer-Policy"           => "no-referrer",
		"X-Content-Type-Options"    => "nosniff",
		"X-Frame-Options"           => "DENY",
		"X-XSS-Protection"          => "1; mode=block",
		"Strict-Transport-Security" => "max-age=31536000; preload",
		"Connection"                => "close"

	);

	private $_RESOURCE = "";                       // Requested resource (String)

	private $_RESOURCE_TYPE = "";

	private $_HEADER_STREAM = "";

	private function request_uri_exists(): bool {

		$exists = (
			file_exists($this->request_uri_absolute_path) &&
			strpos($this->request_uri_absolute_path, $this->document_root_dir) !== false
		);

		return ($exists) ? true : false;
	
	}

	private function request_uri_accessible(): bool {

		$readable = (
			is_readable($this->request_uri_absolute_path)
		);
		$writable = (
			is_writable($this->request_uri_absolute_path)
		);
		
		return ($readable && $writable) ? true : false;
	
	}


	public function __construct(\HTTP\Request $Request, String $document_root_dir, Array $document_index_files) {

		if ($Request == null) {
			throw new \Exception("\HTTP\Request cannot be NULL");
		}
		$this->Request = $Request;
		$this->set_document_root_dir($document_root_dir);
		$this->set_document_index_files($document_index_files);
		$this->set_request_uri_absolute_path($this->Request->get_uri());

		$this->push_header("Date", substr(date(DATE_RFC2822), 0, -5)."GMT");

		$this->set_status_code();
		if (strcmp($this->_STATUS_CODE, \HTTP\HTTP::STATUS_OK) === 0) {
			$this->set_resource();
		} else {
			$this->_RESOURCE_TYPE = "text/html; charset=UTF-8";
			$this->_RESOURCE = "<h1>".$this->_STATUS_CODE."</h1>";
		}
		$this->set_header_stream();

	}

	public function set_document_root_dir(String $document_root_dir): void {

		$this->document_root_dir = realpath($document_root_dir.DIRECTORY_SEPARATOR);
	
	}

	public function get_document_root_dir(): String {

		return $this->document_root_dir;
	
	}

	public function set_document_index_files(Array $document_index_files = array()): void {

		$files = (count($document_index_files) === 0) ? array("index.html") : $document_index_files;

		$this->document_index_files = $files;
	
	}

	public function get_document_index_files(): Array {
	
		return $this->document_index_files;
	
	}

	public function set_request_uri_absolute_path(String $request_uri): void {

		$path = $this->document_root_dir.DIRECTORY_SEPARATOR.$request_uri;

		if (strcmp(trim($request_uri), "/") === 0) {
			for ($i = 0; $i < count($this->document_index_files); $i++) {
				$path_tmp = $path.trim($this->document_index_files[$i]);
				if (file_exists($path_tmp)) {
					$path = $path_tmp;
					break;
				}
			}
		}

		$path = str_replace("../", "/", $path);
		$this->request_uri_absolute_path = realpath($path);
	
	}

	public function get_request_uri_absolute_path(): String {
		
		return $this->request_uri_absolute_path;

	}

	public function set_status_code(): void {

		$method = "\HTTP\HTTP::METHOD_".strtoupper($this->Request->get_method());
		if (!defined($method)) {
			$this->_STATUS_CODE = \HTTP\HTTP::STATUS_METHOD_NOT_ALLOWED;
			return;
		}

		if ($this->request_uri_exists()) {
			if (!$this->request_uri_accessible()) {
				$this->_STATUS_CODE = \HTTP\HTTP::STATUS_FORBIDDEN;
				return;
			}
		} else {
			$this->_STATUS_CODE = \HTTP\HTTP::STATUS_NOT_FOUND;
			return;
		}
		
		$this->_STATUS_CODE = \HTTP\HTTP::STATUS_OK;
		
	}

	public function get_status_code(): String {
	
		return $this->status_code;

	}

	public function set_resource(): void {

		$method = $this->Request->get_method();
		$_RESOURCE = "";

		switch (strtoupper(trim($method))) {

		case (\HTTP\HTTP::METHOD_GET):
			$_RESOURCE = $this->get();
			break;
		
		case (\HTTP\HTTP::METHOD_DELETE):
			$_RESOURCE = $this->delete();
			break;

		};

		$extension = pathinfo($this->request_uri_absolute_path)["extension"];
		$this->_RESOURCE_TYPE = constant("\HTTP\HTTP::TYPE_".strtoupper($extension));
		$this->_RESOURCE = $_RESOURCE;
	
	}

	public function get_resource(): String {
	
		return $this->_RESOURCE;

	}

	public function get_resource_type(): String {
	
		return $this->_RESOURCE_TYPE;
	
	}

	public function set_header_stream(): void {

		$_HEADER_STREAM = "";
		$_EOL = chr(0x0d).chr(0x0a);

		$this->push_header("Content-Type", $this->_RESOURCE_TYPE."; UTF-8");
		$this->push_header("Content-Length", strlen($this->_RESOURCE));

		$_HEADER_STREAM .= \HTTP\HTTP::PROTOCOL.chr(0x20).$this->_STATUS_CODE.$_EOL;
		foreach ($this->_HEADER as $key => $value) {
			$_HEADER_STREAM .= $key.chr(0x3a).chr(0x20).$value.$_EOL;
		}
		$_HEADER_STREAM .= $_EOL;
		$_HEADER_STREAM .= $this->_RESOURCE;

		$this->_HEADER_STREAM = $_HEADER_STREAM;

	}

	public function get_header_stream(): String {

		return $this->_HEADER_STREAM;
	
	}

	public function push_header(String $key, String $value): void {
	
		$this->_HEADER[strtolower(trim($key))] = trim($value);
	
	}

	public function pop_header(String $key): bool {
	
		if (array_key_exists($this->_HEADER, trim($key))) {
			unset($this->_HEADER[$key]);
			return true;
		}

		return false;
	
	}

	public function set_header(Array $header): void {
	
		$this->_HEADER = $header;

	}

	public function get_header(): Array {

		return $this->_HEADER;

	}


	/** HTTP Method GET */
	private function get(): String {

		$resource = file_get_contents($this->request_uri_absolute_path);

		return $resource;

	}


	/** HTTP Method DELETE */
	private function delete(): String {

		$success = "False";

		if (is_dir($this->request_uri_absolute_path)) {
			$success = rmdir($this->request_uri_absolute_path) ? "True" : "False";
		}
		if (is_file($this->request_uri_absolute_path)) {
			$success = unlink($this->request_uri_absolute_path) ? "True" : "False";
		}

		return $success;

	}

};
