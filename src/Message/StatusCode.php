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

};
