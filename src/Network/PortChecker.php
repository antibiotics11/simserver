<?php

namespace simserver\Network;

class PortChecker {

  public static function isValidPort(int $port): bool {
    return ($port > 0 && $port <= 65535);
  }

  public static function isPortInUse(int $port, InetAddress $inetAddress, int $protocol = SOL_TCP): bool {

    return !self::isPortAvailable($port, $inetAddress, $protocol);

  }

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
