<?php

namespace HTTP;

class SocketServer {

	const DEFAULT_SERVER_NAME  = "127.0.0.1";
	const DEFAULT_PORT_NUMBER  = 80;
	const DEFAULT_DOC_ROOT     = "/var/www";
	const DEFAULT_LOG_DIR      = __DIR__;
	
	const SOCKET_BUFFER_SIZE   = 4096;


	private ?\Socket      $httpSocket       = null;
	private ?InetAddress  $serverName       = null;
	private int           $listeningPort    = 0;
	
	private Array         $documentIndex    = [];
	private String        $documentRoot     = "";
	private String        $log              = "";
	
	
	public function __construct(String $serverConfig = "") {

		if (strlen($serverConfig) > 0) {
			$this->create($serverConfig);
		}	
	
	}
	
	public function __destruct() {
	
		$this->shutdown();
	
	}

	public function create(String $config): void {
	
		$config = json_decode($config);
		
		$serverName = $config->ServerName ?? SocketServer::DEFAULT_SERVER_NAME;
		$serverName = gethostbyname($serverName);
		try {
			$this->serverName = new InetAddress($serverName);
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
		
		$this->listeningPort = $config->ListeningPort ?? SocketServer::DEFAULT_PORT_NUMBER;
		$this->listeningPort = (int)$this->listeningPort;
		if (!SocketServer::isValidPort($this->listeningPort)) {
			throw new \Exception("Port number must be between 0 and 65535.");
		}
		
		$this->documentRoot  = $config->DocumentRoot ?? SocketServer::DEFAULT_DOC_ROOT;
		$this->documentIndex = $config->DocumentIndex ?? "";
		$this->log           = $config->Log ?? SocketServer::DEFAULT_LOG_DIR;
		
		$domain = $this->serverName->getVersion();
		$this->httpSocket = socket_create($domain, SOCK_STREAM, SOL_TCP);
		if ($e = socket_last_error()) {
			throw new \Exception("Failed to create socket: ".socket_strerror($e));
		}
		
		socket_bind($this->httpSocket, $this->serverName->getAddress(), $this->listeningPort);
		if ($e = socket_last_error()) {
			throw new \Exception("Failed to bind to socket: ".socket_strerror($e));
		}
		
	}
	
	public function listen(): void {
	
		if ($this->httpSocket == null) {
			throw new \Exception("Socket is not set.");
		}
		
		socket_listen($this->httpSocket);
		if ($e = socket_last_error()) {
			throw new \Exception("Failed to listen on socket: ".socket_strerror($e));
		}

		while ($connection = socket_accept($this->httpSocket)) {
		
			$request         = null;
			$response        = null;
			$message         = null;
			$requestPacket   = "";
			$responsePacket  = "";
		
			socket_getpeername($connection, $remoteIp, $remotePort);
			
			$requestPacket = socket_read($connection, SocketServer::SOCKET_BUFFER_SIZE);
			if ($requestPacket === false) {
				throw new SocketIOException(socket_last_error());
				socket_close($connection);
			}
			
			try {
			
				$request  = new Request($requestPacket);
				$response = new Response($request, [
					"document_root" => $this->documentRoot, 
					"document_index" => $this->documentIndex 
				]);
				$message  = $response->getMessage();
				
			} catch (\Throwable $e) {
			 
				Logger::writeLog($this->log, "Internal Server Error: ".$e->getMessage());
				
				$status   = new Status(Status::STATUS_INTERNAL_SERVER_ERROR);
				$message  = Message::createResponseStatusMessage($status);
				
			}
			
			$responsePacket = Message::packResponseMessage($message);
			
			if (socket_write($connection, $responsePacket) === false) {
				throw new SocketIOException(socket_last_error());
			}
			
			Logger::writeLog($this->log, $remoteIp." - \""
				.$request->getMessage()->method." "
				.$request->getMessage()->path." "
				.$request->getMessage()->protocol."\" "
				.$message->status->code." ".$message->header[Message::HEADER_CONTENT_LENGTH]
			);
			
			socket_close($connection);
		
		}
		
	
	}
	
	public function shutdown(): void {
	
		socket_close($this->httpSocket);
		
		if (is_dir($this->log) && is_writable($this->log)) {
			Logger::writeLogBuffer($this->log);
		}
	
	}
	
	public function getServerName(): ?InetAddress {
	
		return $this->serverName;
	
	}
	
	public function getListeningPort(): int {
	
		return $this->listeningPort;
	
	}

	public static function getSystemTime(): String {
	
		return substr(date(DATE_RFC2822), 0, -5).date("T");
	
	}

	public static function isValidPort(int $port): bool {

		return ($port >= 1 && $port <= 65535);
	
	}

};
