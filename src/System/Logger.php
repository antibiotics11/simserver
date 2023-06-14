<?php

namespace simserver\System;

class Logger {

  private const LOG_BUFFER_MAX_SIZE  = 1000;
  private const LOG_ENTRY_MAX_LENGTH = 512;

	private String $directory;

	private Array  $buffer;
	private int    $bufferSize;

  private bool   $printToConsole;

  private function getLogFilePath(): String {

    $filename = sprintf("%s.log", Time::DateYMD());
    $path = sprintf("%s/%s", $this->directory, $filename);

    return $path;

  }

	public function __construct(
    String $directory, bool $createDirectory = true,
    bool $printToConsole = true,
    int $bufferSize = self::LOG_BUFFER_MAX_SIZE
  ) {

    $this->setDirectory($directory, $createDirectory);
		$this->resetBuffer();
		$this->setBufferSize($bufferSize);
    $this->printToConsole = $printToConsole;

	}

  public function resetBuffer(): void {
    $this->buffer = [];
  }

  public function setDirectory(String $directory, bool $createDirectory = true): void {

    if (!is_dir($directory) || !is_writable($directory)) {
      if (!$createDirectory) {
        throw new \InvalidArgumentException("Directory does not exist or is not writable.");
      }
      if (!mkdir($directory, 0777, true)) {
        throw new \InvalidArgumentException("Failed to create directory.");
      }
    }

    $absolutePath = realpath($directory);
    if ($absolutePath === false) {
      throw new \InvalidArgumentException("Directory path cannot be resolved.");
    }

    $this->directory = $absolutePath;

  }

	public function getDirectory(): String {
		return $this->directory;
	}

	public function setBufferSize(int $bufferSize): void {

		if ($bufferSize < 1 || $bufferSize > self::LOG_BUFFER_MAX_SIZE) {
			throw new \InvalidArgumentException(
        sprintf("Buffer size must be between 1 and %d.", self::LOG_BUFFER_MAX_SIZE)
			);
		}
		$this->bufferSize = $bufferSize;

	}

	public function getBufferSize(): int {
		return $this->bufferSize;
	}

	public function write(String $expression): void {

    if (mb_strlen($expression) > self::LOG_ENTRY_MAX_LENGTH) {
      $expression = mb_substr($expression, 0, self::LOG_ENTRY_MAX_LENGTH);
    }
		$this->buffer[] = $expression;

    $this->writeBufferToFile(false);

	}

  public function writeBufferToFile(bool $ignoreBufferSize = true): bool {

    if (!$ignoreBufferSize) {
      if (count($this->buffer) < $this->bufferSize) {
        return false;
      }
    }

    $logFilePath = $this->getLogFilePath();
    $logFileContents = sprintf("%s%s", implode(PHP_EOL, $this->buffer), PHP_EOL);
    $this->resetBuffer();

    return file_put_contents($logFilePath, $logFileContents, FILE_APPEND | LOCK_EX);

  }

	public static function print(String $expression, bool $isError = false): void {
		$color = $isError ? 31 : 34;
		printf("\033[0;%sm%s\033[0m\r\n", $color, $expression);
	}

};
