<?php

namespace HTTP;
use \HTTP\{InetAddress, Socket};
use \HTTP\{Status, StaticResource};
use \HTTP\{Request, Response};

class Server {

	private ?InetAddress $address = null;
	private String $name = "";
	private int    $port = -1;

	private String $admin = "";

	private String $doc_root = "";
	private Array  $doc_index = [];

	private String $log = "";

	private function parse_array(Array $host): void {
	
		foreach ($host as $index => $value) {
			
			$index = strtolower(trim($index));
			switch ($index) {
			
			case "name" :
				$this->name = $value;
				$addr = gethostbyname($value);
				try {
					$this->address = new InetAddress($addr);
				} catch (\Exception $e) {
					throw new \Exception($e);
				}
				break;

			case "port" :
				if (!Socket::is_valid_port($value)) {
					throw new \Exception(
						"Invalid port number \"".$value."\""
					);
				}
				$this->port = $value; break;

			case "admin" : 
				$this->admin = $value; break;

			case "doc_root" :
				$this->doc_root = $value; break;

			case "doc_index" :
				$this->doc_index = explode(",", $value);
				break;

			case "log" :
				$this->log = $value; break;
			
			}

		}
	
	}

	public function __construct(Array $host) {
		$this->parse_array($host);
	}

	public function run(): void {
		
		$socket = new Socket();

		$socket->create($this->address, $this->port);
		$socket->listen(function($stream) {

			$request = new Request($stream);
			$response = new Response(
				$request, 
				$this->doc_root, 
				$this->doc_index
			);
					
			return $response->complete_header();

		});

	}
	
};
