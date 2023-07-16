<?php

require_once "../src/Network/StreamServer.php";
require_once "../src/Network/InetAddress.php";
require_once "../src/Security/CertificateUtils.php";
use simserver\Network\{InetAddress, StreamServer};

$server = new StreamServer(InetAddress::getByAddress("127.0.0.1"), 443);
$server->setSecureServer("cert/server.pem", "cert/server.key");
$server->setRequestMaxSize(1024 * 4);
$server->setPersistentConnection(true);
$server->setStreamBlocking(false);

$server->listen(function(String $request, String $client): Array {
  
  $httpHeader = [
    "HTTP/1.1 200 OK",
    "Server: php",
    "Connection: keep-alive",
    "Content-Type: image/png",
    "Transfer-Encoding: chunked",
    "\r\n"
  ];

  $chunkStream = [ implode("\r\n", $httpHeader) ];
 
  $imageFile = file_get_contents("root/http.png");
  $truncated = "";
  $chunk = "";

  // Split image into chunks and add each chunk to the chunk array
  for ($i = 0; $i < strlen($imageFile); $i += 1024) {
    $truncated = substr($imageFile, $i, 1024);
    $chunk = sprintf("%x\r\n%s\r\n", strlen($truncated), $truncated);
    $chunkStream[] = $chunk;
  }
  $chunkStream[] = "0\r\n\r\n";
  
  printf("%d chunks sent to %s\r\n", count($chunkStream) - 1, $client);
  
  return $chunkStream;

});

