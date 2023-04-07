<?php

namespace simserver\Message;
use simserver\Exception\ParseException;

class Message {

  public bool   $request      = false;
  public String $protocol     = "HTTP/1.0";

  public String $path         = "";
  public String $method       = "";
  public int    $status       = 200;

  public Array  $header       = [];
  public String $body         = "";


  public static function parseRequest(String $packet): Message {

    $lines = explode(chr(0x0d).chr(0x0a), $packet);
    if (count($lines) < 2) {
      throw new ParseException("Invalid header format");
    }

    $message = new Message();
    $message->request = true;
    $message->status = StatusCode::STATUS_OK;

    $startLine = explode(chr(0x20), $lines[0]);
    if (count($startLine) != 3) {
      throw new ParseException("Invalid header format");
    }

    $message->method = strtoupper($startLine[0]);
    if (strlen($message->method) < 3) {
      throw new ParseException("Undefined request method");
    }

    $message->path = trim($startLine[1]);
    if (strlen($message->path) < 1) {
      throw new ParseException("Undefined request path");
    }

    $message->protocol = trim($startLine[2]);
    if (strpos($message->protocol, "HTTP/") === false) {
      throw new ParseException("Undefined protocol");
    }

    $message->header = [];
    $bodyIndex = 0;
    for ($header = 1; $header < count($lines); $header++) {

      $headerName = "";
      $headerValue = "";
      $tmp = "";
      $separatorFound = false;

      for ($f = 0; $f < strlen($lines[$header]); $f++) {
        if (ord($lines[$header][$f]) == 0x3a && !$separatorFound) {
          $separatorFound = true;
          $headerName = trim($tmp);
          $tmp = "";
          continue;
        }
        $tmp = sprintf("%s%s", $tmp, $lines[$header][$f]);
      }
      $headerValue = $tmp;

      if (strlen($headerName) == 0 || strlen($headerValue) == 0) {
        throw new ParseException("Invalid header field");
      }

      $message->header[$headerName] = $headerValue;

      if (strlen($lines[$header + 1]) == 0) {
        $bodyIndex = $header + 2;
        break;
      }

    }

    $message->body = implode(chr(0x0d).chr(0x0a), array_slice($lines, $bodyIndex));

    return $message;


  }

  public static function packResponse(Message $message): String {


  }


  public const SERVER_HTTP_VERSION = "HTTP/1.0";
  public const SERVER_SOFTWARE     = "simserver";

};
