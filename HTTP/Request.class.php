<?php

namespace HTTP;

class Request {

	const METHOD_GET    = "GET";
	const METHOD_POST   = "POST";
	const METHOD_HEAD   = "HEAD";

	private Array $header = [];

	public function __construct(String $request = "") {
		if (strlen($request) !== 0) {
			$this->parse($request);
		}
	}

	public function parse(String $request): Array {
		
		$lines = explode("\n", $request);
		$line = [];
		$header = [];

		for ($i = 0; $i < count($lines); $i++) {

			if ($i == 0) {
				$line = explode(" ", $lines[$i]);
				$header["METHOD"] = $line[0];
				$header["URI"] = $line[1];
				$header["PROTOCOL"] = $line[2];
			} else {
				$line = explode(":", $lines[$i]);
				if (!isset($line[1])) {
					continue;
				}
				if (strlen($line[1]) <= 0) {
					continue;
				}
				$line[0] = strtoupper(trim($line[0]));
				$header[$line[0]] = trim($line[1]);
			}
		
		}

		$this->header = $header;
		
		return $this->header;

	}

	public function get_header(): Array {
		return $this->header;
	}

	public function get_protocol(): String {
		return $this->header["PROTOCOL"];
	}

	public function get_method(): String {
		return $this->header["METHOD"];
	}

	public function get_uri(): String {
		return $this->header["URI"];
	}

	public function get_host(): String {
		return $this->header["HOST"];
	}

	public function get_user_agent(): String {
		$agent = &$this->header["USER-AGENT"];
		return (isset($agent)) ? $agent : ""; 
	}

	public function get_accept_language(): String {
		$lang = &$this->header["ACCEPT-LANGUAGE"];
		return (isset($lang)) ? $lang : "";
	}

};
