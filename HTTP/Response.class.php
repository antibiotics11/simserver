<?php

namespace HTTP;
use \HTTP\{Request, Status, StaticResource};

class Response {

	const PROTOCOL   = "HTTP/1.0";

	const STATUS_OK                      = 200;
	//const STATUS_CREATED                 = 201;
	//const STATUS_ACCEPTED                = 202;
	//const STATUS_NO_CONTENT              = 204;
	//const STATUS_MOVED_PERMANENTLY       = 301;
	//const STATUS_MOVED_TEMPORARILY       = 302;
	//const STATUS_BAD_REQUEST             = 400;
	//const STATUS_UNAUTHORIZED            = 401;
	const STATUS_FORBIDDEN               = 403;
	const STATUS_NOT_FOUND               = 404;
	const STATUS_METHOD_NOT_ALLOWED      = 405;
	//const STATUS_PAYLOAD_TOO_LARGE       = 413;
	//const STATUS_URI_TOO_LONG            = 404;
	//const STATUS_INTERNAL_SERVER_ERROR   = 500;
	//const STATUS_NOT_IMPLEMENTED         = 501;
	//const STATUS_BAD_GATEWAY             = 502;
	//const STATUS_SERVICE_UNAVAILABLE     = 503;

	private ?Request $request = null;
	private ?Status  $status = null;

	private Array $header = [
		"Connection"             => "close",
		"Pragma"                 => "no-cache",
		"Server"                 => "simserver",
		"Accept-Ranges"          => "bytes",
		"Content-Encoding"       => "UTF-8",
	];

	private String $uri_absolute_path = "";

	private String $doc_root  = "";
	private Array  $doc_index = [];

	private ?StaticResource $resource = null;

	public static function get_system_time(): String {
		//return (date("D, d M Y G:i:s T"));
		return substr(date(DATE_RFC2822), 0, -5).date("T");
	}

	private function uri_exists(): bool {

		return (
			file_exists($this->uri_absolute_path) &&
			strpos($this->uri_absolute_path, $this->doc_root) !== false
		) ? true : false;
	
	}

	private function uri_accessible(): bool {

		if (is_readable($this->uri_absolute_path) &&
			is_writable($this->uri_absolute_path)) {
			return true;
		}
		return false;

	}

	public function __construct(Request $request, String $doc_root, Array $doc_index) {

		$this->request = $request;

		$this->set_doc_root($doc_root);
		$this->set_doc_index($doc_index);
		$this->set_uri_absolute_path($this->request->get_uri());

		$this->status = new Status();
		$this->set_status();
		$this->set_resource();

		$this->complete_header();

	}

	public function set_doc_root(String $doc_root): void {
		$this->doc_root = $doc_root;
	}

	public function get_doc_root(): String {
		return $this->doc_root;
	}

	public function set_doc_index(Array $doc_index): void {

		if (count($doc_index) === 0) {
			$doc_index = [ "index.html" ];
		}
		$this->doc_index = $doc_index;

	}

	public function get_doc_index(): Array {
		return $this->doc_index;
	}

	public function set_uri_absolute_path(String $uri): void {

		$uri = str_replace("..", "/", $uri);
		$path = $this->doc_root."/".$uri;
		$pathinfo = pathinfo($path);

		$dirname = $pathinfo["dirname"];
		$basename = $pathinfo["basename"];

		if (strcmp(trim($uri), "/") === 0) {
			for ($i = 0; $i < count($this->doc_index); $i++) {
				$path_tmp = $path.trim($this->doc_index[$i]);
				if ($path_tmp = realpath($path_tmp)) {
					$this->uri_absolute_path = $path_tmp;
					return;
				}
			}
		}

		$this->uri_absolute_path = $dirname."/".$basename;

	}

	public function get_uri_absolute_path(): String {
		return $this->uri_absolute_path;
	}

	public function set_status(): void {
		
		$method = "\HTTP\Request::METHOD_".strtoupper(
			$this->request->get_method()
		);
		if (!defined($method)) {
			$this->status->code = Response::STATUS_METHOD_NOT_ALLOWED;
			$this->status->message = "Method Not Allowed";
			return;
		}

		if ($this->uri_exists()) {
			if (!$this->uri_accessible()) {
				$this->status->code = Response::STATUS_FORBIDDEN;
				$this->status->message = "Forbidden";
				return;
			}
		} else {
			$this->status->code = Response::STATUS_NOT_FOUND;
			$this->status->message = "Not Found";
			return;
		}

		$this->status->code = Response::STATUS_OK;
		$this->status->message = "OK";

	}

	public function get_status(): Status {
		return $this->status;
	}

	public function set_resource(): void {

		$method = $this->request->get_method();
		$this->resource = new StaticResource();

		if ($this->status->code !== Response::STATUS_OK) {
			$this->resource->set_resource(Status::page($this->status));
			$this->resource->set_type("HTML");
			return;
		}

		switch (strtoupper(trim($method))) {

		case (Request::METHOD_GET) :
			$this->resource->new_resource($this->uri_absolute_path);
			break;

		case (Request::METHOD_HEAD) : 
			break;

		case (Request::METHOD_POST) :
			break;
		
		};

	}

	public function get_resource(): String {
		return $this->resource;
	}

	public function push_header(String $index, String $value): void {
		$this->header[strtoupper($index)] = $value;
	}

	public function pop_header(String $index): bool {

		$index = strtoupper(trim($index));
		if (array_key_exists($this->header, $index)) {
			unset($this->header[$index]);
			return true;
		}
		return false;
	}

	public function complete_header(): String {

		$header = "";
		$eol = "\r\n";
		$resource = $this->resource->get_resource();

		$this->push_header("Date", $this->get_system_time());
		$this->push_header("Content-Type", $this->resource->get_type());
		$this->push_header("Content-Length", strlen($resource));

		$header .= Response::PROTOCOL." ".$this->status->code." ".$eol;
		foreach ($this->header as $index => $value) {
			$header .= $index.": ".$value.$eol;
		}
		$header .= $eol;
		$header .= $resource;

		return $header;
	
	}

};
