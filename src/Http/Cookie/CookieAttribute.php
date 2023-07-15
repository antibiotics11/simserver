<?php

namespace simserver\Http\Cookie;

enum CookieAttribute: String {

  case DOMAIN   = "Domain";
  case PATH     = "Path";
  case SECURE   = "Secure";
  case HTTPONLY = "HttpOnly";

  /**
   * HTTP/1.1+ Cookie Attributes
   */
  case MAX_AGE  = "Max-Age";
  case EXPIRES  = "Expires";
  case SAMESITE = "SameSite";

};
