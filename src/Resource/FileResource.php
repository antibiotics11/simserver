<?php

namespace simserver\Resource;
use InvalidArgumentException;

final class FileResource extends Resource {

  private String $filePath;
  private String $inputPath;

  private int    $changeTime;
  private int    $modificationTime;
  private int    $accessTime;

  public function __construct(String $path) {
    $this->read($path);
  }

  public function read(String $path): void {

    $this->inputPath = trim($path);
    $this->setFilePath($this->inputPath);
    
    $pathinfo = pathinfo($this->filePath, PATHINFO_ALL);
    $this->setMimeType($pathinfo["extension"] ?? "txt");
    
    $content = @file_get_contents($this->filePath, false);
    if ($content === false) {
      throw new InvalidArgumentException("Failed to read the file.");
    }
    $this->setContent($content);

    $this->changeTime = filectime($this->filePath);
    $this->modificationTime = filemtime($this->filePath);
    $this->accessTime = fileatime($this->filePath);

  }
  
  public function setFilePath(String $path): void {

    $absolutePath = realpath($path);
    if ($absolutePath === false) {
      throw new InvalidArgumentException("Invalid file path provided.");
    }
    if (is_dir($absolutePath) || !is_readable($absolutePath)) {
      throw new InvalidArgumentException("Invalid file path provided.");
    }
    $this->filePath = $absolutePath;

  }
  
  public function getInputPath(): String {
    return $this->inputPath;
  }
  
  public function getFilePath(): String {
    return $this->filePath;
  }
  
  public function getChangeTime(): int {
    return $this->changeTime;
  }

  public function getModificationTime(): int {
    return $this->modificationTime;
  }

  public function getAccessTime(): int {
    return $this->accessTime;
  }

};
