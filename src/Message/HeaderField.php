<?php

namespace simserver\Message;

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
   * Response Header Fields
   */
  case LOCATION           = "Location";
  case SERVER             = "Server";
  case WWW_AUTHENTICATE   = "WWW-Authenticate";
  case RETRY_AFTER        = "Retry-After";

};
