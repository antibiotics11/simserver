<?php

namespace HTTP;

class Socket {

	private ?\Socket     $http_socket = null;
	
	private ?InetAddress $address = null;
	private int          $port = -1;

	public static function is_valid_port(int $port): bool {
		return ($port >= 1 && $port <= 65535) ? true : false;
	}

	public function create(InetAddress $address, int $port): void {

		$this->address = $address;
		if (Socket::is_valid_port($port)) {
			$this->port = $port;
		} else {
			throw new \Exception("Invalid port number \"".$port."\"");
		}

		$domain = $this->address->get_version();
		$type = \SOCK_STREAM;
		$protocol = \SOL_TCP;

		$this->http_socket = socket_create($domain, $type, $protocol);
		if ($e = socket_last_error()) {
			throw new \Exception(
				"Failed to create socket: "
				.socket_strerror($e)
			);
		}

		socket_bind($this->http_socket, $address->get_address(), $this->port);
		if ($e = socket_last_error()) {
			throw new \Exception(
				"Failed to bind to socket: "
				.socket_strerror($e)
			);
		}
	
	}

	public function listen(\Closure $process): void {
	
		if ($this->http_socket == null) {
			throw new \Exception("Socket is not set.");
		}
		
		socket_listen($this->http_socket);
		while ($connection = socket_accept($this->http_socket)) {
		
			socket_getpeername($connection, $ip, $port);

			$request = socket_read($connection, 4096);
			
			$response = $process($request, $ip);

			socket_write($connection, $response);

			socket_close($connection);

		}

	}

};
