<?php

namespace simserver\Http;

enum StatusCode: int {

  case OK                          = 200;  // Ok
  case CREATED                     = 201;  // Created
  case ACCEPTED                    = 202;  // Accepted
  case NO_CONTENT                  = 204;  // No Content

  case MOVED_PERMANENTLY           = 301;  // Moved Permanently
  case MOVED_TEMPORARILY           = 302;  // Moved Temporarily (Found in HTTP/1.1)
  case NOT_MODIFIED                = 304;  // Not Modified

  case BAD_REQUEST                 = 400;  // Bad Request
  case UNAUTHORIZED                = 401;  // Unauthorized
  case FORBIDDEN                   = 403;  // Forbidden
  case NOT_FOUND                   = 404;  // Not Found

  case INTERNAL_SERVER_ERROR       = 500;  // Internal Server Error
  case NOT_IMPLEMENTED             = 501;  // Not Implemented
  case BAD_GATEWAY                 = 502;  // Bad Gateway
  case SERVICE_UNAVAILABLE         = 503;  // Service Unavailable

  /**
   * Converts status code to its corresponding message.
   *
   * @param int|self $statusCode status code
   * @return string  status message
   */
  public static function toMessage(int | self $statusCode): String {

    if ($statusCode instanceof self) {
      $statusCode = $statusCode->value;
    }

    return match ($statusCode) {
      self::OK->value                    => "OK",
      self::CREATED->value               => "Created",
      self::ACCEPTED->value              => "Accepted",
      self::NO_CONTENT->value            => "No Content",
      self::MOVED_PERMANENTLY->value     => "Moved Permanently",
      self::MOVED_TEMPORARILY->value     => "Moved Temporarily",
      self::NOT_MODIFIED->value          => "Not Modified",
      self::BAD_REQUEST->value           => "Bad Request",
      self::UNAUTHORIZED->value          => "Unauthorized",
      self::FORBIDDEN->value             => "Forbidden",
      self::NOT_FOUND->value             => "Not Found",
      self::INTERNAL_SERVER_ERROR->value => "Internal Server Error",
      self::NOT_IMPLEMENTED->value       => "Not Implemented",
      self::BAD_GATEWAY->value           => "Bad Gateway",
      self::SERVICE_UNAVAILABLE->value   => "Service Unavailable",
      default                                 => ""
    };

  }

};
