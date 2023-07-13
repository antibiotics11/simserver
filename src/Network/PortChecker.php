<?php

namespace simserver\Network;

class PortChecker {

  /**
   * Check If port number is valid (1-65535).
   *
   * @param int $port port number
   * @return bool True if port number is valid, false otherwise
   */
  public static function isValidPort(int $port): bool {
    return ($port > 0 && $port <= 65535);
  }

  /**
   * Check if a specific port is in use on given IP address.
   *
   * @param int         $port        port number to check
   * @param InetAddress $inetAddress IP address to check
   * @param int         $protocol    protocol to use (SOL_TCP in default)
   * @return bool True if port is in use, false otherwise
   */
  public static function isPortInUse(int $port, InetAddress $inetAddress, int $protocol = SOL_TCP): bool {
    return !self::isPortAvailable($port, $inetAddress, $protocol);
  }

  /**
   * Check if a specific port is available on given IP address.
   *
   * @param int         $port        port number to check
   * @param InetAddress $inetAddress IP address to check
   * @param int         $protocol    protocol to use (SOL_TCP in default)
   * @return bool True if port is available, false otherwise
   */
  public static function isPortAvailable(int $port, InetAddress $inetAddress, int $protocol = SOL_TCP): bool {

    $address = $inetAddress->getAddress();
    $addressFamily = $inetAddress->getAddressFamily();
    $testSocketType = match ($protocol) {
      SOL_TCP => SOCK_STREAM,
      SOL_UDP => SOCK_DGRAM,
      default => SOCK_RAW
    };

    $testSocket = @socket_create($addressFamily, $testSocketType, $protocol);
    if ($testSocket === false) {
      return false;
    }

    $testBindingResult = @socket_bind($testSocket, $address, $port);
    if ($testBindingResult) {
      socket_close($testSocket);
      return true;
    }
    return false;

  }

};
