<?php

namespace simserver\Http;

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
  public int    $status             = StatusCode::OK;

  public Array  $header             = [];
  public String $body               = "";


  public static function parseRequest(String $data): Message {

    $lines = explode(self::CHR_CR_LF, $data);
    $linesCount = count($lines);
    if ($linesCount < 2) {
      throw new \InvalidArgumentException("Invalid message format.");
    }

    $message = new Message();
    $message->request = true;

    $startLine = explode(self::CHR_BLANK, trim($lines[0]));
    if (count($startLine) < 2 || count($startLine) > 3) {
      throw new \InvalidArgumentException("Invalid start line format.");
    }

    $message->method = strtoupper($startLine[0]);
    if (strlen($message->method) < 3) {
      throw new \InvalidArgumentException("Invalid method.");
    }

    $message->path = trim($startLine[1]);
    if (strlen($message->path) < 1) {
      throw new \InvalidArgumentException("Invalid path.");
    }

    $startLine[2] ??= self::DEFAULT_PROTOCOL;
    $message->protocol = strtoupper(trim($startLine[2]));
    if (strpos($message->protocol, "HTTP/") === false) {
      throw new \InvalidArgumentException("Invalid protocol.");
    }

    $message->header = [];
    $bodyIndex = 0;
    for ($header = 1; $header < $linesCount; $header++) {

      $headerLine   = $lines[$header];
      $headerLength = strlen($headerLine);
      $headerName   = "";
      $headerValue  = "";
      $tmp = "";
      $separatorFound = false;

      if ($header == $linesCount -1 && $headerLength == 0) {
        break;
      }

      for ($f = 0; $f < $headerLength; $f++) {
        if (strcmp($headerLine[$f], self::CHR_COLON) == 0 && !$separatorFound) {
          $separatorFound = true;
          $headerName = trim($tmp);
          $tmp = "";
        } else {
          $tmp = sprintf("%s%s", $tmp, $headerLine[$f]);
        }
      }
      $headerValue = trim($tmp);

      if (strlen($headerName) == 0 || strlen($headerValue) == 0) {
        throw new \InvalidArgumentException("Invalid header format.");
      }

      $message->header[$headerName] = $headerValue;

      $nextLine = $lines[$header + 1] ?? "";
      if (strcmp($nextLine, self::CHR_CR_LF) === 0 || strlen($nextLine) == 0) {
        $bodyIndex = $header + 2;
        break;
      }

    }

    $message->body = "";
    if ($bodyIndex > 0) {
      $message->body = trim(implode(self::CHR_CR_LF,
        array_slice($lines, $bodyIndex)
      ));
    }

    return $message;

  }

  public static function packResponse(Message $message): String {


  }

};
