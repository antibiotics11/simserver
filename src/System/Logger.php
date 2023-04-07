<?php

namespace simserver\System;

class Logger {

  private String $directory;

  private Array $logBuffer;
  private int   $logBufferSize;

  private function resetLogBuffer(): void {
    $this->logBuffer = [];
  }

  public function __construct(int $logBufferSize = 100, String $directory = "log/") {
    $this->resetLogBuffer();
    $this->setLogBufferSize($logBufferSize);
    $this->setDirectory($directory);
  }

  public function setDirectory(String $directory): void {

    $directoryAbsolutePath = realpath($directory);
    if ($directoryAbsolutePath === false) {
      $created = mkdir($directory, 0777);
      if (!$created) {
        throw new \InvalidArgumentException("");
      }
      $directoryAbsolutePath = realpath($directory);
    }

    $this->directory = $directoryAbsolutePath;

  }

  public function getDirectory(): String {
    return $this->directory;
  }

  public function setLogBufferSize(int $logBufferSize): void {

    if ($logBufferSize < 1 || $logBufferSize > PHP_INT_MAX) {
      throw new \InvalidArgumentException(
        sprintf("Buffer size must be between 1 and %d", PHP_INT_MAX)
      );
    }
    $this->logBufferSize = $logBufferSize;

  }

  public function getLogBufferSize(): int {
    return $this->logBufferSize;
  }

  public function write(String $expression): void {

    $this->buffer[] = $expression;
    if ($count($this->buffer) >= $this->logBufferSize) {
      if (strlen($this->directory) == 0) {
        return;
      }
      $result = $this->writeLogBufferToFile();
      if ($result) {
        $this->resetLogBuffer();
      }
    }

  }

  public function writeLogBufferToFile(): bool {

    $filepath = sprintf("%s/%s.log", $this->directory, date("Y-m-d"));
    $contents = sprintf("%s\r\n", implode("\r\n", $this->logBuffer));
    $written = file_put_contents($filepath, $contents, FILE_APPEND);

    return $written ? true : false;

  }

  public static function print(String $expression, int $color = -1): void {
    $color = ($color != -1 && $color >= 30) ? $color : self::ANSI_FONT_RESET;
    printf("\033[0;%sm%s\033[0m\r\n", $color, $expression);
  }

  public const ANSI_FONT_RESET     = 0;
  public const ANSI_FONT_BLACK     = 30;
  public const ANSI_FONT_RED       = 31;
  public const ANSI_FONT_GREEN     = 32;
  public const ANSI_FONT_YELLOW    = 33;
  public const ANSI_FONT_BLUE      = 34;
  public const ANSI_FONT_PURPLE    = 35;
  public const ANSI_FONT_CYAN      = 36;
  public const ANSI_FONT_WHITE     = 37;

};
