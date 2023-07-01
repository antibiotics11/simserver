<?php

namespace simserver\Resource;

class MimeType {

	public const TYPE_AAC    = "audio/aac";
	public const TYPE_ABW    = "application/x-abiword";
	public const TYPE_ARC    = "application/x-freearc";
	public const TYPE_AVIF   = "image/avif";
	public const TYPE_AVI    = "video/x-msvideo";
	public const TYPE_AZW    = "application/vnd.amazon.ebook";
	public const TYPE_BIN    = "application/octet-stream";
	public const TYPE_BMP    = "image/bmp";
	public const TYPE_BZ     = "application/x-bzip";
	public const TYPE_BZ2    = "application/x-bzip2";
	public const TYPE_CDA    = "application/x-cdf";
	public const TYPE_CSH    = "application/x-csh";
	public const TYPE_CSS    = "text/css";
	public const TYPE_CSV    = "text/csv";
	public const TYPE_DOC    = "application/msword";
	public const TYPE_DOCX   = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
	public const TYPE_EOT    = "application/vnd.ms-fontobject";
	public const TYPE_EPUB   = "application/epub+zip";
	public const TYPE_GZ     = "application/gzip";
	public const TYPE_GIF    = "image/gif";
	public const TYPE_HTM    = "text/html";
	public const TYPE_HTML   = "text/html";
	public const TYPE_ICO    = "image/vnd.microsoft.icon";
	public const TYPE_ICS    = "text/calendar";
	public const TYPE_JAR    = "application/java-archive";
	public const TYPE_JPG    = "image/jpeg";
	public const TYPE_JPEG   = "image/jpeg ";
	public const TYPE_JS     = "text/javascript";
	public const TYPE_JSON   = "application/json";
	public const TYPE_JSONLD = "application/ld+json";
	public const TYPE_MID    = "audio/midi";
	public const TYPE_MIDI   = "audio/x-midi";
	public const TYPE_MJS    = "text/javascript";
	public const TYPE_MP3    = "audio/mpeg";
	public const TYPE_MP4    = "video/mp4";
	public const TYPE_MPEG   = "video/mpeg";
	public const TYPE_MPKG   = "application/vnd.apple.installer+xml";
	public const TYPE_ODP    = "application/vnd.oasis.opendocument.presentation";
	public const TYPE_ODS    = "application/vnd.oasis.opendocument.spreadsheet";
	public const TYPE_ODT    = "application/vnd.oasis.opendocument.text";
	public const TYPE_OGA    = "audio/ogg";
	public const TYPE_OGV    = "video/ogg";
	public const TYPE_OGX    = "application/ogg";
	public const TYPE_OPUS   = "audio/opus";
	public const TYPE_OTF    = "font/otf";
	public const TYPE_PNG    = "image/png";
	public const TYPE_PDF    = "application/pdf";
	public const TYPE_PPT    = "application/vnd.ms-powerpoint";
	public const TYPE_PPTX   = "application/vnd.openxmlformats-officedocument.presentationml.presentation";
	public const TYPE_RAR    = "application/vnd.rar";
	public const TYPE_RTF    = "application/rtf";
	public const TYPE_SH     = "application/x-sh";
	public const TYPE_SVG    = "image/svg+xml";
	public const TYPE_TIF    = "image/tiff ";
	public const TYPE_TAR    = "application/x-tar";
	public const TYPE_TIFF   = "image/tiff";
	public const TYPE_TS     = "video/mp2t";
	public const TYPE_TTF    = "font/ttf";
	public const TYPE_TXT    = "text/plain";
	public const TYPE_VSD    = "application/vnd.visio";
	public const TYPE_WAV    = "audio/wav";
	public const TYPE_WEBA   = "audio/webm";
	public const TYPE_WEBM   = "audio/webm";
	public const TYPE_WEBP   = "image/webp";
	public const TYPE_WOFF   = "font/woff";
	public const TYPE_WOFF2  = "font/woff2";
	public const TYPE_XHTML  = "application/xhtml+xml";
	public const TYPE_XLS    = "application/vnd.ms-excel";
	public const TYPE_XLSX   = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
	public const TYPE_XML    = "application/xml";
	public const TYPE_XUL    = "application/vnd.mozilla.xul+xml";
	public const TYPE_ZIP    = "application/zip";
	public const TYPE_3GP    = "video/3gpp";
	public const TYPE_3G2    = "video/2gpp2";
	public const TYPE_7Z     = "application/x-7z-compressed";

	public const TYPE_HWP    = "application/vnd.hancom.hwp";
	public const TYPE_HWPX   = "application/vnd.hancom.hwpx";
	public const TYPE_ALZ    = "application/zip";
	public const TYPE_EGG    = "application/zip";


	private static ?\ReflectionClass $reflector    = null;
	private static Array             $typeConstants = [];

	public static function resetReflector(): void {
		self::$reflector = new \ReflectionClass(self::class);
	}

	public static function resetTypeConstants(): void {
		if (self::$reflector === null) {
			self::resetReflector();
		}
		self::$typeConstants = self::$reflector->getConstants();
	}

	public static function fromName(String $typeName): String {

		if (count(self::$typeConstants) == 0) {
			self::resetTypeConstants();
		}

		$typeName = strtoupper(trim($typeName));
		$constantName = sprintf("TYPE_%s", $typeName);
		return self::$typeConstants[$constantName] ?? "";

	}

	public static function fromValue(String $typeValue): Array {

		if (count(self::$typeConstants) == 0) {
			self::resetTypeConstants();
		}

		$typeValue = strtolower(trim($typeValue));
		$constantName = [];
		foreach (self::$typeConstants as $name => $value) {
			if (strcmp($typeValue, $value) === 0) {
				$constantName[] = $name;
			}
		}

		return $constantName;

	}

};
