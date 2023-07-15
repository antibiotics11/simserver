<?php

namespace simserver\Http;

enum HeaderField: String {

  /**
   * General Header Fields
   */
  case DATE               = "Date";
  case PRAGMA             = "Pragma";

  /**
   * Entity Header Fields
   */
  case ALLOW              = "Allow";
  case CONTENT_ENCODING   = "Content-Encoding";
  case CONTENT_LENGTH     = "Content-Length";
  case CONTENT_TYPE       = "Content-Type";
  case EXPIRES            = "Expires";
  case LAST_MODIFIED      = "Last-Modified";
  case CONTENT_LANGUAGE   = "Content-Language";
  case LINK               = "Link";
  case TITLE              = "Title";

  /**
   * Request Header Fields
   */
  case AUTHORIZATION      = "Authorization";
  case FROM               = "From";
  case IF_MODIFIED_SINCE  = "If-Modified-Since";
  case REFERER            = "Referer";
  case USER_AGENT         = "User-Agent";
  case ACCEPT             = "Accept";
  case ACCEPT_CHARSET     = "Accept-Charset";
  case ACCEPT_ENCODING    = "Accept-Encoding";
  case ACCEPT_LANGUAGE    = "Accept-Language";

  /**
   * HTTP/1.1+ Cache Headers
   */
  case ETAG               = "ETag";
  case IF_MATCH           = "If-Match";
  case IF_NONE_MATCH      = "If-None-Match";

  /**
   * Response Header Fields
   */
  case LOCATION           = "Location";
  case SERVER             = "Server";
  case WWW_AUTHENTICATE   = "WWW-Authenticate";
  case RETRY_AFTER        = "Retry-After";


  /**
   * Tries to convert a http header field string to corresponding HeaderField instance.
   *
   * @param string $headerFieldStr http header field string to convert
   * @return HeaderField|null corresponding HeaderField instance, or null if no match is found
   */
  public static function tryFromStr(String $headerFieldStr): ?self {

    foreach (self::cases() as $headerField) {
      if (strcasecmp($headerFieldStr, $headerField->value)) {
        return $headerField;
      }
    }
    return null;

  }

};
