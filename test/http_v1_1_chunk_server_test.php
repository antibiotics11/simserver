<?php

require_once "../src/Exception/ServerException.php";
require_once "../src/Network/StreamServer.php";
require_once "../src/Network/InetAddress.php";
require_once "../src/Security/CertificateUtils.php";
require_once "../src/System/Logger.php";
use simserver\Network\{InetAddress, StreamServer};

$server = new StreamServer(InetAddress::getByAddress("127.0.0.1"), 443);
$server->setSecureServer("localhost_certificate/server.pem", "localhost_certificate/server.key");
$server->setRequestMaxSize(1024 * 4);
$server->setPersistentConnection(true);
$server->setStreamBlocking(false);

$logger = new simserver\System\Logger("./");
$logger->print("Starting HTTP/1.1 chunk encoding test server at 127.0.0.1:443");

$server->listen(function(String $request, String $client) use ($logger): Array {
  
  $httpHeader = [
    "HTTP/1.1 200 OK",
    "Server: php",
    "Connection: keep-alive",
    "Content-Type: image/png",
    "Transfer-Encoding: chunked",
    "\r\n"
  ];

  $chunkStream = [ implode("\r\n", $httpHeader) ];
 
  $imageFile = file_get_contents("document_root/http.png");
  $truncated = "";
  $chunk = "";

  // Split image into chunks and add each chunk to the chunk array
  for ($i = 0; $i < strlen($imageFile); $i += 1024) {
    $truncated = substr($imageFile, $i, 1024);
    $chunk = sprintf("%x\r\n%s\r\n", strlen($truncated), $truncated);
    $chunkStream[] = $chunk;
  }
  $chunkStream[] = "0\r\n\r\n";
  
  $logger->print(sprintf("%d chunks sent to %s", count($chunkStream) - 1, $client));
  
  return $chunkStream;

});

