<?php

namespace simserver\Config;

/**
 * Http Server Configurations
 */
const HTTP_SERVER_ADDRESS      = "127.0.0.1";                    // Server IP address
const HTTP_SERVER_PORT         = 80;                             // Server listening port
const HTTP_SERVER_LOGS         = "logs/";                        // Directory path for server logs

/**
 * Https Server Configurations
 */
const ENABLE_HTTPS             = true;                           // Enable HTTPS (Http over SSL/TLS)
const ENABLE_HTTPS_REDIRECTION = true;                           // Redirect all connections to HTTPS

const LOCAL_CERTIFICATE        = "test/cert/server.crt";         // Path to certificate file  (required)
const LOCAL_KEY                = "test/cert/server.key";         // Path to private key file  (required)

const HTTPS_SERVER_PORT        = 443;                            // Https server listening port
const HTTPS_SERVER_LOGS        = "logs/";                        // Directory Path for https server logs

/**
 * Document Configurations
 */
const DOCUMENT_ROOT            = "test/root/";                   // Document root dicrectory
const DOCUMENT_INDEX           = [ "index.html", "index.htm" ];  // Index files to look for in the document root (in PHP Array)

const DOCUMENT_403_PAGE        = "text/root/403.html";           // Path to custom 403 Forbidden page
const DOCUMENT_404_PAGE        = "text/root/404.html";           // Path to custom 404 Not Found page

/**
 * HTTP Method Configurations
 */
const ENABLE_METHOD_HEAD       = true;                           // Enable HTTP HEAD Method
const ENABLE_METHOD_GET        = true;                           // Enable HTTP GET Method
const ENABLE_METHOD_POST       = false;                          // Enable HTTP POST Method
const ENABLE_METHOD_PUT        = false;                          // Enable HTTP PUT Method
const ENABLE_METHOD_DELETE     = false;                          // Enable HTTP DELETE Method

/**
 * Server software identifier
 * Modify it if you don't want the server identifier to be exposed.
 */
const SERVER_SOFTWARE          = "simserver";

/**
 * Maximum size of resources to load into memory (in bytes)
 */
const RESOURCE_CACHE_MAX_SIZE  = 1024 * 1024 * 10;

/**
 * Maximum size of request body (in bytes)
 */
const REQUEST_BODY_MAX_SIZE    = 1024 * 8;

/**
 * Maximum time for connection to timeout (in seconds)
 */
const REQUEST_TIMEOUT          = 0.2;
