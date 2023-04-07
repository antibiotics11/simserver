<?php

namespace simserver\Resource;

class MimeType {

  public const _AAC    = "audio/aac";
  public const _ABW    = "application/x-abiword";
  public const _ARC    = "application/x-freearc";
  public const _AVIF   = "image/avif";
  public const _AVI    = "video/x-msvideo";
  public const _AZW    = "application/vnd.amazon.ebook";
  public const _BIN    = "application/octet-stream";
  public const _BMP    = "image/bmp";
  public const _BZ     = "application/x-bzip";
  public const _BZ2    = "application/x-bzip2";
  public const _CDA    = "application/x-cdf";
  public const _CSH    = "application/x-csh";
  public const _CSS    = "text/css";
  public const _CSV    = "text/csv";
  public const _DOC    = "application/msword";
  public const _DOCX   = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
  public const _EOT    = "application/vnd.ms-fontobject";
  public const _EPUB   = "application/epub+zip";
  public const _GZ     = "application/gzip";
  public const _GIF    = "image/gif";
  public const _HTM    = "text/html";
  public const _HTML   = "text/html";
  public const _ICO    = "image/vnd.microsoft.icon";
  public const _ICS    = "text/calendar";
  public const _JAR    = "application/java-archive";
  public const _JPG    = "image/jpeg";
  public const _JPEG   = "image/jpeg ";
  public const _JS     = "text/javascript";
  public const _JSON   = "application/json";
  public const _JSONLD = "application/ld+json";
  public const _MID    = "audio/midi";
  public const _MIDI   = "audio/x-midi";
  public const _MJS    = "text/javascript";
  public const _MP3    = "audio/mpeg";
  public const _MP4    = "video/mp4";
  public const _MPEG   = "video/mpeg";
  public const _MPKG   = "application/vnd.apple.installer+xml";
  public const _ODP    = "application/vnd.oasis.opendocument.presentation";
  public const _ODS    = "application/vnd.oasis.opendocument.spreadsheet";
  public const _ODT    = "application/vnd.oasis.opendocument.text";
  public const _OGA    = "audio/ogg";
  public const _OGV    = "video/ogg";
  public const _OGX    = "application/ogg";
  public const _OPUS   = "audio/opus";
  public const _OTF    = "font/otf";
  public const _PNG    = "image/png";
  public const _PDF    = "application/pdf";
  public const _PPT    = "application/vnd.ms-powerpoint";
  public const _PPTX   = "application/vnd.openxmlformats-officedocument.presentationml.presentation";
  public const _RAR    = "application/vnd.rar";
  public const _RTF    = "application/rtf";
  public const _SH     = "application/x-sh";
  public const _SVG    = "image/svg+xml";
  public const _TIF    = "image/tiff ";
  public const _TAR    = "application/x-tar";
  public const _TIFF   = "image/tiff";
  public const _TS     = "video/mp2t";
  public const _TTF    = "font/ttf";
  public const _TXT    = "text/plain";
  public const _VSD    = "application/vnd.visio";
  public const _WAV    = "audio/wav";
  public const _WEBA   = "audio/webm";
  public const _WEBM   = "audio/webm";
  public const _WEBP   = "image/webp";
  public const _WOFF   = "font/woff";
  public const _WOFF2  = "font/woff2";
  public const _XHTML  = "application/xhtml+xml";
  public const _XLS    = "application/vnd.ms-excel";
  public const _XLSX   = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
  public const _XML    = "application/xml";
  public const _XUL    = "application/vnd.mozilla.xul+xml";
  public const _ZIP    = "application/zip";
  public const _3GP    = "video/3gpp";
  public const _3G2    = "video/2gpp2";
  public const _7Z     = "application/x-7z-compressed";

  public static function fromName(String $name): String {

    try {
      return constant(sprintf("self::_%s", strtoupper(trim($name))));
    } catch (\Throwable $e) {
      return self::_TXT;
    }

  }

};
