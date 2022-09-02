<?php

namespace HTTP;

Interface HTTP {

	const PROTOCOL                    = "HTTP/1.0";

	const METHOD_GET                  = "GET";
	//const METHOD_POST                 = "POST";
	//const METHOD_PUT                  = "PUT";
	const METHOD_DELETE               = "DELETE";
	//const METHOD_PATCH                = "PATCH";

	const STATUS_OK                   = "200 OK";
	const STATUS_FORBIDDEN            = "403 Forbidden";
	const STATUS_NOT_FOUND            = "404 Not Found";
	const STATUS_METHOD_NOT_ALLOWED   = "405 Method Not Allowed";

	const TYPE_XML                    = "text/xml";
	const TYPE_HTM                    = "text/html";
	const TYPE_HTML                   = "text/html";
	const TYPE_TXT                    = "text/plain";
	const TYPE_CSS                    = "text/css";
	const TYPE_SCSS                   = "text/scss";
	const TYPE_JS                     = "text/javascript";
	const TYPE_PDF                    = "application/pdf";
	const TYPE_ZIP                    = "application/zip";
	const TYPE_JSON                   = "application/json";
	const TYPE_EOT                    = "application/vnd.ms-fontobject";
	const TYPE_TTF                    = "application/font-sfnt";
	const TYPE_WOFF                   = "application/font-woff";
	const TYPE_WOFF2                  = "application/font-woff2";
	const TYPE_JPG                    = "image/jpg";
	const TYPE_JPEG                   = "image/jpeg";
	const TYPE_PNG                    = "image/png";
	const TYPE_SVG                    = "image/svg+xml";
	const TYPE_GIF                    = "image/gif";

};
