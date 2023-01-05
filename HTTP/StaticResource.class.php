<?php

namespace HTTP;

class StaticResource {

	private String $type     = "";
	private String $path     = "";
	private String $resource = "";
	
	public function __construct(String $path = "") {
	
		if (strlen($path) !== 0) {
			$this->setNewResource($path);
		}
	
	}
	
	public function setNewResource(String $path): bool {
	
		if ($path = realpath($path)) {
			$this->path = $path;
		} else {
			return false;
		}
		
		if ($resource = file_get_contents($path)) {
			$this->resource = $resource;
		} else {
			return false;
		}
		
		$info = pathinfo($this->path);
		$info["extension"] = $info["extension"] ?? "txt";
		
		$extension = strtoupper($info["extension"]);
		try {
			$this->type = constant("\HTTP\StaticResource::TYPE_".$extension);
		} catch (\Throwable $e) {
			$this->type = StaticResource::TYPE_TXT;
		}
		
		return true;
	
	}
	
	public function getType(): String {
	
		return $this->type;
	
	}
	
	public function getPath(): String {
	
		return $this->path;
	
	}
	
	public function getResource(): String {
	
		return $this->resource;
	
	}
	
	public static function getResourceLastModifiedTime(String $path): String {
	
		return substr(date(DATE_RFC2822, filemtime($path)), 0, -5).date("T");
	
	}
	
	public static function resourceExists(String $path): bool {
	
		return (file_exists($path));
	
	}
	
	public static function resourceAccessible(String $path): bool {
	
		return (is_readable($path) && is_writable($path));
	
	}
	
	
	/**
	  * Common MIME types
	  * https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Common_types
	  */
	const TYPE_AAC    = "audio/aac";
	const TYPE_ABW    = "application/x-abiword";
	const TYPE_ARC    = "application/x-freearc";
	const TYPE_AVIF   = "image/avif";
	const TYPE_AVI    = "video/x-msvideo";
	const TYPE_AZW    = "application/vnd.amazon.ebook";
	const TYPE_BIN    = "application/octet-stream";
	const TYPE_BMP    = "image/bmp";
	const TYPE_BZ     = "application/x-bzip";
	const TYPE_BZ2    = "application/x-bzip2";
	const TYPE_CDA    = "application/x-cdf";
	const TYPE_CSH    = "application/x-csh";
	const TYPE_CSS    = "text/css";
	const TYPE_CSV    = "text/csv";
	const TYPE_DOC    = "application/msword";
	const TYPE_DOCX   = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
	const TYPE_EOT    = "application/vnd.ms-fontobject";
	const TYPE_EPUB   = "application/epub+zip";
	const TYPE_GZ     = "application/gzip";
	const TYPE_GIF    = "image/gif";
	const TYPE_HTM    = "text/html";
	const TYPE_HTML   = "text/html";
	const TYPE_ICO    = "image/vnd.microsoft.icon";
	const TYPE_ICS    = "text/calendar";
	const TYPE_JAR    = "application/java-archive";
	const TYPE_JPG    = "image/jpeg";
	const TYPE_JPEG   = "image/jpeg";
	const TYPE_JS     = "text/javascript";
	const TYPE_JSON   = "application/json";
	const TYPE_JSONLD = "application/ld+json";
	const TYPE_MID    = "audio/midi";
	const TYPE_MIDI   = "audio/x-midi";
	const TYPE_MJS    = "text/javascript";
	const TYPE_MP3    = "audio/mpeg";
	const TYPE_MP4    = "video/mp4";
	const TYPE_MPEG   = "video/mpeg";
	const TYPE_MPKG   = "application/vnd.apple.installer+xml";
	const TYPE_ODP    = "application/vnd.oasis.opendocument.presentation";
	const TYPE_ODS    = "application/vnd.oasis.opendocument.spreadsheet";
	const TYPE_ODT    = "application/vnd.oasis.opendocument.text";
	const TYPE_OGA    = "audio/ogg";
	const TYPE_OGV    = "video/ogg";
	const TYPE_OGX    = "application/ogg";
	const TYPE_OPUS   = "audio/opus";
	const TYPE_OTF    = "font/otf";
	const TYPE_PNG    = "image/png";
	const TYPE_PDF    = "application/pdf";
	//const TYPE_PHP    = "application/x-httpd-php";
	const TYPE_PPT    = "application/vnd.ms-powerpoint";
	const TYPE_PPTX   = "application/vnd.openxmlformats-officedocument.presentationml.presentation";
	const TYPE_RAR    = "application/vnd.rar";
	const TYPE_RTF    = "application/rtf";
	const TYPE_SH     = "application/x-sh";
	const TYPE_SVG    = "image/svg+xml";
	const TYPE_TAR    = "application/x-tar";
	const TYPE_TIF    = "image/tiff";
	const TYPE_TIFF   = "image/tiff";
	const TYPE_TS     = "video/mp2t";
	const TYPE_TTF    = "font/ttf";
	const TYPE_TXT    = "text/plain";
	const TYPE_VSD    = "application/vnd.visio";
	const TYPE_WAV    = "audio/wav";
	const TYPE_WEBA   = "audio/webm";
	const TYPE_WEBM   = "audio/webm";
	const TYPE_WEBP   = "image/webp";
	const TYPE_WOFF   = "font/woff";
	const TYPE_WOFF2  = "font/woff2";
	const TYPE_XHTML  = "application/xhtml+xml";
	const TYPE_XLS    = "application/vnd.ms-excel";
	const TYPE_XLSX   = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
	const TYPE_XML    = "application/xml";
	const TYPE_XUL    = "application/vnd.mozilla.xul+xml";
	const TYPE_ZIP    = "application/zip";
	const TYPE_3GP    = "video/3gpp";
	const TYPE_3G2    = "video/2gpp2";
	const TYPE_7Z     = "application/x-7z-compressed";
	
};
