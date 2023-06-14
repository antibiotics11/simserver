<?php

namespace simserver\Message;

class StatusCode {

  public const STATUS_OK                          = 200;
  public const STATUS_CREATED                     = 201;
  public const STATUS_ACCEPTED                    = 202;
  public const STATUS_NO_CONTENT                  = 204;

  public const STATUS_MOVED_PERMANENTLY           = 301;
  public const STATUS_MOVED_TEMPORARILY           = 302;
  public const STATUS_NOT_MODIFIED                = 304;

  public const STATUS_BAD_REQUEST                 = 400;
  public const STATUS_UNAUTHORIZED                = 401;
  public const STATUS_FORBIDDEN                   = 403;
  public const STATUS_NOT_FOUND                   = 404;

  public const STATUS_INTERNAL_SERVER_ERROR       = 500;
  public const STATUS_NOT_IMPLEMENTED             = 501;
  public const STATUS_BAD_GATEWAY                 = 502;
  public const STATUS_SERVICE_UNAVAILABLE         = 503;

  public static function toMessage(int $statusCode): String {
    switch ($statusCode) {
      case self::STATUS_OK                    : return "OK";
      case self::STATUS_CREATED               : return "Created";
      case self::STATUS_ACCEPTED              : return "Accepted";
      case self::STATUS_NO_CONTENT            : return "No Content";
      case self::STATUS_MOVED_PERMANENTLY     : return "Moved Permanently";
      case self::STATUS_MOVED_TEMPORARILY     : return "Moved Temporarily";
      case self::STATUS_NOT_MODIFIED          : return "Not Modified";
      case self::STATUS_BAD_REQUEST           : return "Bad Request";
      case self::STATUS_UNAUTHORIZED          : return "Unauthorized";
      case self::STATUS_FORBIDDEN             : return "Forbidden";
      case self::STATUS_NOT_FOUND             : return "Not Found";
      case self::STATUS_INTERNAL_SERVER_ERROR : return "Internal Server Error";
      case self::STATUS_NOT_IMPLEMENTED       : return "Not Implemented";
      case self::STATUS_BAD_GATEWAY           : return "Bad Gateway";
      case self::STATUS_SERVICE_UNAVAILABLE   : return "Service Unavailable";
      default                                 : return "";
    };
  }

};
