<?php

namespace simserver\Server;
use simserver\Network\InetAddress;
use simserver\Exception\{SocketException, SocketIOException};

class StreamServer {
	
	private                $streamSocket;
	//private ?\Socket       $streamSocket;
	private ?InetAddress   $serverName;
	private int            $listeningPort;

	private int            $socketBufferSize;

	public function __construct() {

		$this->streamSocket  = null;
		$this->serverName    = null;
		$this->listeningPort = -1;

	}

	public function getServerName(): ?InetAddress {
		return $this->serverName;
	}

	public function getListeningPort(): int {
		return $this->listeningPort;
	}

	public function create(InetAddress $serverName, int $listeningPort): void {

		$this->serverName = $serverName;
		$this->listeningPort = $listeningPort;

		$this->streamSocket = socket_create(
			$this->serverName->getFamily(),
			SOCK_STREAM,
			SOL_TCP
		);
		if ($e = socket_last_error()) {
			throw new SocketException(socket_strerror($e));
		}

		socket_bind(
			$this->streamSocket,
			$this->serverName->getAddress(),
			$this->listeningPort
		);
		if ($e = socket_last_error()) {
			throw new SocketException(socket_strerror($e));
		}

	}

	public function listen(?\Closure $process): void {

		if ($this->streamSocket === null) {
			throw new SocketException("Socket is not set");
		}

		socket_listen($this->streamSocket);
		if ($e = socket_last_error()) {
			throw new SocketException(socket_strerror($e));
		}

		while (true) {

			$connection = socket_accept($this->streamSocket);
			if ($connection === false) {
				continue;
			}

			socket_getpeername(
				$connection,
				$remoteIp,
				$remotePort
			);

			$requestPacket = socket_read(
				$connection,
				(10 * 1024 * 1024),
				PHP_BINARY_READ
			);
			if ($requestPacket === false) {
				throw new SocketIOException(socket_last_error());
				socket_close($connection);
				continue;
			}

			$responsePacket = $process(
				$requestPacket,
				$remoteIp,
				$remotePort
			);
			if (socket_write($connection, $responsePacket) === false) {
				throw new SocketIOException(socket_last_error());
			}

			socket_close($connection);

		}

	}

	public function block(): bool {
		return socket_set_block($this->streamSocket);
	}

	public function nonblock(): bool {
		return socket_set_nonblock($this->streamSocket);
	}

	public function close(): void {
	
		socket_clear_error($this->streamSocket);
		if (socket_shutdown($this->streamSocket, 2)) {
			socket_close($this->streamSocket);
		} else {
			throw new SocketException("Failed to shut down socket");
		}
		
	}

	public function terminate(): void {
		$this->close();
		exit(0);
	}

};
