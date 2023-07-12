<?php

namespace simserver\Server;
use simserver\Network\InetAddress;
use simserver\Exception\SocketException;

class StreamServer {

	private Mixed $streamSocket;                      // (resource) server stream socket
	private Mixed $streamContextOptions;              // (resource) server stream socket context options

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

	/**
	 * Creates a server socket at the specified address and port
	 *
	 * @param  InetAddress $address The IP address to bind the server socket.
	 * @param  int         $port    The Port number to bind the server socket.
	 * @throws SocketException      If the server creation fails.
	 */
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

		// Set the initial state of the server
		$this->isConnectionPersistingServer = false;
		$this->setRequestMaxSize(1024 * 4);
		$this->setStreamBlocking(false);

	}

	/**
	 * Listen for incoming connections and handles requests.
	 *
	 * @param \Closure      $requestHandler            A closure that handles incoming requests and returns response.
	 * @param \Closure|null $unencryptedRequestHandler A closure that handles any cryptographic errors.
	 */
	public function listen(\Closure $requestHandler, ?\Closure $unencryptedRequestHandler = null): void {

		$server = [ $this->streamSocket ];         // server stream
		$connections = [];                         // client streams (persistent connections)

		while (true) {

			 // Clean up closed or abnormal streams from the connections array
			foreach ($connections as $i => $connection) {
				if (!is_resource($connection)) {
					unset($connections[$i]);
				}
			}

			$read   = array_merge($server, array_values($connections));
			$write  = null;
			$except = null;
			if (stream_select($read, $write, $except, 2) === false) {
				continue;
			}

			foreach ($read as $i => $stream) {

				$client             = null;            // (stream) client connection
				$clientName         = "";              // (string) client name
				$clientMetadata     = [];              // (array)  client meta data
				$request            = "";              // (string) request data
				$response           = "";              // (string) response data

				if ($stream === $this->streamSocket) {
					// Accept a new client connection if it's the server stream
					$client = stream_socket_accept($this->streamSocket);
					if ($this->isSecureServer) {
						@stream_socket_enable_crypto($client, true, STREAM_CRYPTO_METHOD_ANY_SERVER);
					}
					if ($this->isConnectionPersistingServer) {
						$connections[] = $client;
					}
				} else {
					$client = $stream;
				}

				$clientName = stream_socket_get_name($client, true);
				if ($clientName === false) {
					// Close the client connection if the name retrieval fails.
					fclose($client);
					if ($this->isConnectionPersistingServer) {
						unset($connections[$i]);
					}
					continue;
				}

				$clientMetadata = stream_get_meta_data($client);
				if (!isset($clientMetadata["crypto"]) && $this->isSecureServer) {
					// Handle request if the client connection is not properly encrypted.
					if (is_callable($unencryptedRequestHandler)) {
						$response = $unencryptedRequestHandler($clientName);
					} else {
						continue;
					}
				} else {
					$request = fread($client, $this->requestMaxSize);
					if ($request === false) {
						continue;
					}
					$response = $requestHandler($request, $clientName);
				}

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
