<?php

namespace simserver\Message;

enum StatusCode: int {

  case OK                          = 200;
  case CREATED                     = 201;
  case ACCEPTED                    = 202;
  case NO_CONTENT                  = 204;

  case MOVED_PERMANENTLY           = 301;
  case MOVED_TEMPORARILY           = 302;
  case NOT_MODIFIED                = 304;

  case BAD_REQUEST                 = 400;
  case UNAUTHORIZED                = 401;
  case FORBIDDEN                   = 403;
  case NOT_FOUND                   = 404;

  case INTERNAL_SERVER_ERROR       = 500;
  case NOT_IMPLEMENTED             = 501;
  case BAD_GATEWAY                 = 502;
  case SERVICE_UNAVAILABLE         = 503;

  public static function toMessage(int | self $statusCode): String {

    if ($statusCode instanceof self) {
      $statusCode = $statusCode->value;
    }

    switch ($statusCode) {
      case self::OK->value                    : return "OK";
      case self::CREATED->value               : return "Created";
      case self::ACCEPTED->value              : return "Accepted";
      case self::NO_CONTENT->value            : return "No Content";
      case self::MOVED_PERMANENTLY->value     : return "Moved Permanently";
      case self::MOVED_TEMPORARILY->value     : return "Moved Temporarily";
      case self::NOT_MODIFIED->value          : return "Not Modified";
      case self::BAD_REQUEST->value           : return "Bad Request";
      case self::UNAUTHORIZED->value          : return "Unauthorized";
      case self::FORBIDDEN->value             : return "Forbidden";
      case self::NOT_FOUND->value             : return "Not Found";
      case self::INTERNAL_SERVER_ERROR->value : return "Internal Server Error";
      case self::NOT_IMPLEMENTED->value       : return "Not Implemented";
      case self::BAD_GATEWAY->value           : return "Bad Gateway";
      case self::SERVICE_UNAVAILABLE->value   : return "Service Unavailable";
      default                                 : return "";
    };
  }

};
