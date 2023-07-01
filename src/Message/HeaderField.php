<?php

namespace simserver\Message;

class HeaderField {

  /**
   * General Header Fields
   */
  public const HEADER_DATE               = "Date";
  public const HEADER_PRAGMA             = "Pragma";

  /**
   * Entity Header Fields
   */
  public const HEADER_ALLOW              = "Allow";
  public const HEADER_CONTENT_ENCODING   = "Content-Encoding";
  public const HEADER_CONTENT_LENGTH     = "Content-Length";
  public const HEADER_CONTENT_TYPE       = "Content-Type";
  public const HEADER_EXPIRES            = "Expires";
  public const HEADER_LAST_MODIFIED      = "Last-Modified";
  public const HEADER_CONTENT_LANGUAGE   = "Content-Language";
  public const HEADER_LINK               = "Link";
  public const HEADER_TITLE              = "Title";

  /**
   * Request Header Fields
   */
  public const HEADER_AUTHORIZATION      = "Authorization";
  public const HEADER_FROM               = "From";
  public const HEADER_IF_MODIFIED_SINCE  = "If-Modified-Since";
  public const HEADER_REFERER            = "Referer";
  public const HEADER_USER_AGENT         = "User-Agent";
  public const HEADER_ACCEPT             = "Accept";
  public const HEADER_ACCEPT_CHARSET     = "Accept-Charset";
  public const HEADER_ACCEPT_ENCODING    = "Accept-Encoding";
  public const HEADER_ACCEPT_LANGUAGE    = "Accept-Language";

  /**
   * Response Header Fields
   */
  public const HEADER_LOCATION           = "Location";
  public const HEADER_SERVER             = "Server";
  public const HEADER_WWW_AUTHENTICATE   = "WWW-Authenticate";
  public const HEADER_RETRY_AFTER        = "Retry-After";

};
