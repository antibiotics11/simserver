<?php

namespace simserver\Network;

class PortChecker {

  public static function isPortInUse(int $port, InetAddress $address, int $protocol = \SOL_TCP): bool {

    if (!self::validatePort($port, $address)) {
      return false;
    }
    return !self::isPortAvailable($port, $address, $protocol);

  }

  public static function isPortAvailable(int $port, InetAddress $address, int $protocol = \SOL_TCP): bool {

    $testSocketType = ($protocol == \SOL_TCP) ? \SOCK_STREAM : \SOCK_DGRAM;
    $testSocket = @socket_create($address->getFamily(), $testSocketType, $protocol);
    if ($testSocket === false) {
      return false;
    }

    $testBindingResult = @socket_bind($testSocket, $address->getAddress(), $port);
    socket_close($testSocket);

    return $testBindingResult;

  }

  public static function validatePort(int $port): bool {
    return ($port > 0 && $port <= 65535);
  }

};
