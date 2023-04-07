<?php

namespace simserver\Server;
use simserver\Message\Message;
use simserver\System\{Logger, Time};
use simserver\Exception\ParseException;

class HttpServer {

  private StreamServer  $streamServer;
  private Logger        $logger;

  public function __construct(StreamServer $streamServer) {

    $this->logger = new Logger(500, HTTP_SERVER_LOGS);

    $this->streamServer = $streamServer;
    $this->streamServer->nonblock();

    register_shutdown_function([$this, "shutdown"]);

  }

  public function run(): void {

    $log = sprintf("[%s]\tStrting server at %s:%d",
      Time::DateRFC2822(),
      $this->streamServer->getServerName()->getAddress(),
      $this->streamServer->getListeningPort()
    );

    //$handler = new RequestHandler();

    $this->streamServer->listen(
      function($buffer, $remoteIp, $remotePort) /*use ($handler)*/ {

        $request  = null;
        $response = null;
        $packet   = "";

        try {
          $request = Message::parseRequest($buffer);
          debug_zval_dump($request);
        } catch (ParseException $e) {

        }

      }
    );

  }

  public function shutdown(): void {

  }

};
