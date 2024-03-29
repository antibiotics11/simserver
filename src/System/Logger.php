<?php

namespace simserver\System;
use InvalidArgumentException;

class Logger {

  private String $logDirectory;
  private Array  $logBuffer;
  private int    $logBufferMaxSize;

  private function getLogFilePath(): String {

    $filename = sprintf("%s.log", Time::DateYMD());
    $filepath = sprintf("%s/%s", $this->logDirectory, $filename);

    return $filepath;

  }

  public function __construct(String $logDirectory, int $logBufferMaxSize = 100) {
    $this->setLogDirectory($logDirectory);
    $this->emptyLogBuffer();
    $this->logBufferMaxSize = $logBufferMaxSize;
  }

  public function setLogDirectory(String $logDirectory): void {

    if (!is_dir($logDirectory) || !is_writable($logDirectory)) {
      if (!mkdir($logDirectory, 0777, true)) {
        throw new InvalidArgumentException("Invalid directory or directory is not writable.");
      }
    }

    $absolutePath = realpath($logDirectory);
    if ($absolutePath === false) {
      throw new InvalidArgumentException("Directory path cannot be resolved.");
    }

    $this->logDirectory = $absolutePath;

  }

  public function getLogDirectory(): String {
    return $this->logDirectory;
  }

  public function getLogBuffer(): Array {
    return $this->logBuffer;
  }

  public function getLogBufferSize(): int {
    return count($this->logBufferMaxSize);
  }

  public function emptyLogBuffer(): void {
    $this->logBuffer = [];
  }

  public function write(String $expression): void {

    $this->logBuffer[] = trim($expression);
    if (count($this->logBuffer) >= $this->logBufferMaxSize) {
      $this->writeLogBufferToFile(false);
    }

  }

  public function writeLogBufferToFile(): bool {

    $logFilePath = $this->getLogFilePath();
    $logFileContents = sprintf("%s%s", implode(PHP_EOL, $this->logBuffer), PHP_EOL);

    if (file_put_contents($logFilePath, $logFileContents, FILE_APPEND | LOCK_EX)) {
      $this->emptyLogBuffer();
      return true;
    }
    return false;

  }

  public function print(String $expression, bool $isError = false): void {
    $color = $isError ? 31 : 34;
    printf("\033[0;%sm%s\033[0m\r\n", $color, $expression);
  }

};
