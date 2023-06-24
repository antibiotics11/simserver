<?php

namespace simserver\Message;

class Message {

  private const CHR_CR_LF           = "\r\n";
  private const CHR_TAB             = "\t";
  private const CHR_BLANK           = " ";
  private const CHR_COLON           = ":";

  private const DEFAULT_PROTOCOL    = "HTTP/1.0";
  private const RESPONSE_PROTOCOL   = self::DEFAULT_PROTOCOL;

  public bool   $request            = false;
  public String $protocol           = self::DEFAULT_PROTOCOL;

  public String $path               = "";
  public String $method             = "";
  public int    $status             = StatusCode::STATUS_OK;

  public Array  $header             = [];
  public String $body               = "";


  public static function parseRequest(String $data): Message {

    $lines = explode(self::CRLF, $data);
    if (count($lines) < 2) {
      throw new \InvalidArgumentException("Invalid data format.");
    }

    $message = new Message();
    $message->request = true;
    $message->status = StatusCode::STATUS_OK;

    $startLine = explode(chr(0x20), $lines[0]);
    if (count($startLine) != 3) {
      throw new \InvalidArgumentException("Invalid line format.");
    }

    $message->method = strtoupper($startLine[0]);
    if (strlen($message->method) < 3) {
      throw new \InvalidArgumentException("Invalid method.");
    }

    $message->path = trim($startLine[1]);
    if (strlen($message->path) < 1) {
      throw new \InvalidArgumentException("Invalid path.");
    }

    $message->protocol = strtoupper(trim($startLine[2]));
    if (strpos($message->protocol, "HTTP/") === false) {
      throw new \InvalidArgumentException("Invalid protocol.");
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
        throw new \InvalidArgumentException("Invalid header format.");
      }

      $message->header[$headerName] = $headerValue;

      if (strlen($lines[$header + 1]) == 0) {
        $bodyIndex = $header + 2;
        break;
      }

    }

    $message->body = implode(self::CRLF, array_slice($lines, $bodyIndex));

    return $message;

  }

  public static function packResponse(Message $message): String {


  }

};
