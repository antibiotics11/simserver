<?php

namespace simserver\Server;
use simserver\Network\InetAddress;
use simserver\Exception\SocketException;

class StreamServer {

	private Mixed $streamSocket;                     // resource
	private Mixed $streamContextOptions;             // resource

	private int   $requestMaxSize;
	private bool  $isSecureServer;
	private bool  $isConnectionPersistingServer;

	public function __construct(private InetAddress $address, private int $port) {
		$this->create($address, $port);
		$this->streamContextOptions = null;
		$this->isSecureServer = false;
	}

	public function __destruct() {
		$this->shutdownServer();
	}

	public function create(InetAddress $address, int $port): void {

		$address = sprintf("tcp://%s:%d", $address->getAddress(), $port);
		$this->streamContextOptions ??= stream_context_create([]);

		$this->streamSocket = @stream_socket_server(
			$address,
			$errorCode, $errorMessage,
			STREAM_SERVER_BIND | STREAM_SERVER_LISTEN,
			$this->streamContextOptions
		);

		if ($this->streamSocket === false) {
			throw new SocketException(sprintf("Failed to create server: %s", $errorMessage));
		}

		$this->isConnectionPersistingServer = false;
		$this->setRequestMaxSize(1024 * 4);
		$this->setStreamBlocking(false);

	}

	public function listen(\Closure $requestHandler): void {

		$server = [ $this->streamSocket ];
		$connections = [];

		while (true) {

			foreach ($connections as $i => $connection) {
				if (!is_resource($connection)) {
					unset($connections[$i]);
				}
			}

			$read = array_merge($server, array_values($connections));
			$write = null;
			$except = null;

			if (stream_select($read, $write, $except, 2) === false) {
				continue;
			}

			foreach ($read as $i => $stream) {

				$client = null;

				if ($stream === $this->streamSocket) {
					$client = stream_socket_accept($this->streamSocket);
					if ($this->isSecureServer) {
						stream_socket_enable_crypto($client, true, STREAM_CRYPTO_METHOD_ANY_SERVER);
					}
					if ($this->isConnectionPersistingServer) {
						$connections[] = $client;
					}
				} else {
					$client = $stream;
				}

				$clientName = stream_socket_get_name($client, true);
				if ($clientName === false) {
					fclose($client);
					if ($this->isConnectionPersistingServer) {
						unset($connections[$i]);
					}
					continue;
				}

				$request = fread($client, $this->requestMaxSize);
				if ($request === false) {
					continue;
				}

				$response = $requestHandler($request, $clientName);

				if (fwrite($client, $response) === false) {
					continue;
				}

				if (!$this->isConnectionPersistingServer) {
					fclose($client);
				}

			}

		}

	}

	public function setSecureServer(String $certificatePath = "", String $keyPath = ""): void {

		$certificate = file_get_contents($certificatePath);
		if (openssl_x509_parse($certificate) === false) {
			throw new \SocketException("Failed to create secure server: Invalid certificate.");
		}

		$key = file_get_contents($keyPath);
		if (openssl_pkey_get_private($key) === false) {
			throw new \SocketException("Failed to create secure server: Invalid key.");
		}

		$this->streamContextOptions = stream_context_create([
			"ssl" => [
				"local_cert"           => $certificatePath,
				"local_pk"             => $keyPath,
				"allow_self_signed"    => true,
				"verify_peer"          => false,
				"verify_peer_name"     => false
			]
		]);

		$this->shutdownServer();
		$this->isSecureServer = true;
		$this->create($this->address, $this->port);

	}

	public function setPersistentConnection(bool $persistent = false): void {
		$this->isConnectionPersistingServer = $persistent;
	}

	public function isConnectionPersisting(): bool {
		return $this->isConnectionPersistingServer;
	}

	public function setRequestMaxSize(int $size = 1024 * 4): void {
		$this->requestMaxSize = $size;
	}

	public function getRequestMaxSize(): int {
		return $this->requestMaxSize;
	}

	public function setRequestTimeout(int $seconds, int $microseconds = 0): bool {
		return stream_set_timeout($this->streamSocket, $seconds, $microseconds);
	}

	public function setStreamBlocking(bool $enable = true): bool {
		return stream_set_blocking($this->streamSocket, $enable);
	}

	public function shutdownServer(): void {
		stream_socket_shutdown($this->streamSocket, STREAM_SHUT_RDWR);
	}

};
