<?php

namespace HTTP;

class Socket {

	private ?\Socket $socket = null;

	public function __construct(String $server_name = "", int $listening_port = -1) {
		
		$this->socket = null;
		if ((strlen($server_name) > 0) && ($listening_port != -1 )) {
			$this->create($server_name, $listening_port);
		}

	}

	public static function is_ipv4(string $address = "127.0.0.1"): bool {

		$address = explode(".", $address);

		if (count($address) != 4) {
			return false;
		}

		foreach ($address as $part) {
			if (!is_numeric($part) || (int)$part > 255 || (int)$part < 0) {
				return false;
			}
		}

		return true;

	}

	public static function is_ipv6(string $address = "::1"): bool {

		$address = explode(":", $address);
	
		if (count($address) > 8 || count($address) < 2) {
			return false;
		}

		foreach ($address as $part) {
			if (empty($part)) {
				$part = "0";
				continue;
			}
			if (!ctype_xdigit($part) || (int)hexdec($part) > 65535 || (int)hexdec($part) < 0) {
				return false;
			}
		}

		return true;
			
	}

	public function create(String $server_name, int $listening_port): void {

		try {

			$address = gethostbyname($server_name);
			$domain = -1;
			if (\HTTP\Socket::is_ipv4($address)) {
				$domain = AF_INET;
			} else if (\HTTP\Socket::is_ipv6($address)) {
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
