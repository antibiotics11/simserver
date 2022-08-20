<?php

namespace HTTP;

class Request implements HTTP {

	private $_HEADER = array();

	private $_METHOD = "";

	public function __construct(String $received_stream) {

		$this->parse_received_stream($received_stream);

	}

	public function parse_received_stream(String $received_stream): void {

		$stream_lines = explode("\n", $received_stream);
		$line = array();
		$_HEADER = array();

		for ($i = 0; $i < count($stream_lines); $i++) {

			if ($i == 0) {
				$line = explode(" ", $stream_lines[$i]);
				$_HEADER["METHOD"] = $line[0];
				$_HEADER["URI"] = $line[1];
				$_HEADER["PROTOCOL"] = $line[2];
			} else {
				$line = explode(":", $stream_lines[$i]);
				if (!isset($line[1])) {
					continue;
				}
				if (strlen($line[1]) <= 0) {
					continue;
				}
				$_HEADER[$line[0]] = $line[1];
			}

		}

		$this->_HEADER = $_HEADER;

	}

	public function get_header(): array {

		return $this->_HEADER;
	
	}
	
	public function get_protocol(): String {

		return $this->_HEADER["PROTOCOL"];
	
	}

	public function get_method(): String {
	
		return $this->_HEADER["METHOD"];

	}

	public function get_uri(): String {

		return $this->_HEADER["URI"];

	}

	public function get_host(): String {

		return $this->_HEADER["Host"];
	
	}

	public function get_user_agent(): String {

		$user_agent = &$this->_HEADER["User-Agent"];
		return (isset($user_agent)) ? $this->_HEADER["User-Agent"] : "";
	
	}

	public function get_accept_language(): String {

		$accpet_language = &$this->_HEADER["Accept-Language"];
		return (isset($accpet_language)) ? $this->_HEADER["Accept-Language"] : "";
	
	}

};
