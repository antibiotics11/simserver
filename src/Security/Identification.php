<?php

namespace simserver\Security;

use simserver\Network\{InetAddress, PortChecker};

class Identification {

  private static ?self $identification = null;

  public static function getInstance(float $timeout = 0.3, int $port = 113): self {

    if (self::$identification === null) {
      self::$identification = new self($timeout, $port);
    }
    return self::$identification;

  }


  private float $timeout;
  private int   $port;

  private String $requestMessage;
  private String $responseMessage;

  private function __construct(float $timeout, int $port) {

    $this->timeout = $timeout;
    if (PortChecker::isValidPort($port)) {
      $this->port = $port;
    } else {
      throw new \InvalidArgumentException("Port number must be between 1 and 65535");
    }

    $this->requestMessage = "";
    $this->responseMessage = "";

  }

  public function identify(InetAddress $target, int $portOnServer, int $portOnClient): Array | false {

    $address = sprintf("tcp://%s:%d", $target->getAddress(), $this->port);
    $identSocket = @stream_socket_client($address, $errNo, $errMsg, $this->timeout);
    if ($identSocket === false) {
      return false;
    }

    $identRequest = self::createRequestMessage($portOnServer, $portOnClient);
    if (fwrite($identSocket, $identRequest, strlen($identRequest)) === false) {
      return false;
    }

    $identResponse = fgets($identSocket);
    if ($identResponse === false) {
      return false;
    }

    $this->requestMessage = $identRequest;
    $this->responseMessage = $identResponse;

    return self::parseResponseMessage($identResponse);

  }

  public function getRequestMessage(): String {
    return $this->requestMessage;
  }

  public function getResponseMessage(): String {
    return $this->responseMessage;
  }

  public static function createRequestMessage(int $portOnServer, int $portOnClient): String {

    return sprintf("%d,%d\r\n", $portOnServer, $portOnClient);

  }

  public static function parseResponseMessage(String $message): Array | false {

    $result = [];

    $sets = explode(":", $message);
    if (count($sets) != 3 && count($sets) != 4) {
      return false;
    }

    $portPair = explode(",", trim(array_shift($sets)));
    if (count($portPair) != 2) {
      return false;
    }
    $result["port-on-server"] = (int)$portPair[0];
    $result["port-on-client"] = (int)$portPair[1];

    $result["resp-type"] = trim(mb_strtoupper(array_shift($sets)));
    $result["add-info"]  = trim(mb_strtoupper(array_shift($sets)));

    return $result;

  }

};
