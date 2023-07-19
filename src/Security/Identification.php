<?php

namespace simserver\Security;
use simserver\Network\{InetAddress, PortChecker};
use InvalidArgumentException;

class Identification {

  private static ?self $identification = null;

  public static function getInstance(int $port = 113): self {

    if (self::$identification === null) {
      self::$identification = new self($port);
    }
    return self::$identification;

  }


  private int    $port;
  private String $requestMessage;
  private String $responseMessage;

  private function __construct(int $port) {

    if (PortChecker::isValidPort($port)) {
      $this->port = $port;
    } else {
      throw new InvalidArgumentException("Port number must be between 1 and 65535");
    }

    $this->requestMessage = "";
    $this->responseMessage = "";

  }

  /**
   * Performs identification using Identification protocol (RFC1413).
   *
   * @param InetAddress $target target IP address for identification
   * @param int $portOnServer   server-side port number
   * @param int $portOnClient   client-side port number
   * @return Array|false        identification result as array or false if identification failed
   */
  public function identify(InetAddress $target, int $portOnServer, int $portOnClient): Array | false {

    $address = sprintf("tcp://%s:%d", $target->getAddress(), $this->port);
    $identSocket = @stream_socket_client($address, $errNo, $errMsg);
    if ($identSocket === false) {
      return false;
    }
    stream_set_timeout($identSocket, 0, 200000);
    
    $identRequest = self::createRequestMessage($portOnServer, $portOnClient);
    debug_zval_dump($identRequest);
    if (fwrite($identSocket, $identRequest, strlen($identRequest)) === false) {
      fclose($identSocket);
      return false;
    }

    $identResponse = fgets($identSocket);
    fclose($identSocket);

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

  /**
   * Parses response message received during identification.
   *
   * @param string $message response message
   * @return array|false    parsed response message as array or false if parsing failed
   */
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
