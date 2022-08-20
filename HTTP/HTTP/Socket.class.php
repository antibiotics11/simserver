<?php

namespace HTTP;

class Socket {

	private $socket;

	public function __construct(String $server_name = "", int $listening_port = -1) {
		
		$this->socket = null;
		if ((strlen($server_name) > 0) && ($listening_port != -1 )) {
			$this->create($server_name, $listening_port);
		}

	}

	public function create(String $server_name, int $listening_port): void {

		try {

			$address = gethostbyname($server_name);
			$domain = -1;
			if (\HTTP\IP::is_ipv4($address)) {
				$domain = AF_INET;
			} else if (\HTTP\IP::is_ipv6($address)) {
				$domain = AF_INET6;
			} else {
				throw new \Exception("Cannot get IP Address.");
			}

			$this->socket = socket_create($domain, SOCK_STREAM, SOL_TCP);
			socket_bind($this->socket, $server_name, $listening_port);
		
		} catch (\Exception $e) {
			throw new \Exception("Socket Error: ".$e);
		}

	}

	public function listen(\Closure $process): void {
		
		if ($this->socket == null) {
			throw new \Exception("Socket Error: Socket is null.");
		}
		
		socket_listen($this->socket);
		pcntl_signal(SIGCHLD, SIG_IGN);
		
		while ($connection = socket_accept($this->socket)) {
			
			$pid = pcntl_fork();
			if ($pid == -1) {
				throw new \Exception("Failed to create child process.");
			} else if ($pid == 0) {
				$process($connection);
				socket_close($connection);
			}

		}

	}

};
