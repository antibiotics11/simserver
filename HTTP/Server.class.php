<?php

namespace HTTP;

class Server {

	private ?InetAddress $address = null;
	private String       $name = "";
	private int          $port = -1;

	private String       $admin = "";

	private String       $doc_root = __DIR__;
	private Array        $doc_index = [ "index.html" ];

	private String       $log_dir = "";

	private function parse_config_array(Array $host): void {
	
		foreach ($host as $index => $value) {
			
			$index = strtolower(trim($index));
			switch ($index) {
			
			case "name" :
				$this->name = $value;
				$addr = gethostbyname($value);
				try {
					$this->address = new InetAddress($addr);
				} catch (\Throwable $e) {
					throw new \Exception($e->getMessage());
				}
				break;

			case "port" :
				if (!Socket::is_valid_port($value)) {
					throw new \Exception(
						"Invalid port number \"".$value."\""
					);
				}
				$this->port = $value; 
				break;

			case "admin" : 
				$this->admin = $value; 
				break;

			case "doc_root" :
				$this->doc_root = $value; 
				break;

			case "doc_index" :
				$this->doc_index = explode(",", $value);
				break;

			case "log_dir" :
				if (realpath($this->log_dir)) {
					$this->log_dir = $value;
				}
				break;
			
			}

		}
	
	}

	public function __construct(Array $host) {
		$this->parse_config_array($host);
	}

	public function run(): void {
		
		$socket = new Socket();

		$socket->create($this->address, $this->port);
		$socket->listen(function(String $request, String $ip) {

			$request = new Request($request);
			
			$response = new Response(
				$request, 
				$this->doc_root, 
				$this->doc_index
			);
			
			Logger::write_log(
				$this->log_dir,
				trim($ip)." - "
				."\""
				.trim($request->get_method())." "
				.trim($request->get_uri())." "
				.trim($request->get_protocol())
				."\" "
				.$response->get_status()->code
			);
					
			$response = $response->complete_header();
			
			return $response;

		});

	}
	
};
