<?php

namespace simserver\Resource;

class StaticResource extends Resource {

  private const HASH_TYPES = [ "crc32", "md5", "sha1", "sha256", "sha3-512" ];

  private String $path;
  private Array  $hash;

  private int    $lastAccessed;
  private int    $lastModified;

  public function __construct(String $path = "") {
    if (strlen($path) > 0) {
      $this->create($path);
    }
  }

  public function create(String $path): void {

    $this->setPath($path);
    $pathinfo = pathinfo($path, PathAttribute::pathinfoFlags());

    $this->setType($pathinfo[PathAttribute::EXTENSION] ?? "txt");

    $contents = file_get_contents($this->path);
    if ($contents === false) {
      throw new \InvalidArgumentException("Failed to read file contents.");
    }
    $this->setContents($contents);

    $this->setHash();
    $this->setTimeInfo();

  }


  private function setPath(String $path): void {

    $path = realpath($path);
    if ($path === false) {
      throw new \InvalidArgumentException("Invalid path: Path does not exist.");
    }
    if (is_dir($path)) {
      throw new \InvalidArgumentException("Invalid path: Path is a directory.");
    }

    $this->path = $path;

  }

  public function getPath(): String {
    return $this->path;
  }

  private function setType(String $extension): void {

    $mimeType = MimeType::fromName($extension);
    $this->setMimeType($mimeType);

  }

  private function setHash(): void {

    foreach (self::HASH_TYPES as $algo) {
      $this->hash[$algo] = hash($algo, $this->contents);
    }

  }

  public function getHash(): Array {
    return $this->hash;
  }

  private function setTimeInfo(): void {

    $lastAccessed = fileatime($this->path);
    $lastModified = filemtime($this->path);
    if ($lastAccessed === false || $lastModified === false) {
      throw new InvalidArgumentException("Failed to retrieve file time information.");
    }

    $this->lastAccessed = $lastAccessed;
    $this->lastModified = $lastModified;

  }

  public function getLastAccessed(): int {
    return $this->lastAccessed;
  }

  public function getLastModified(): int {
    return $this->lastModified;
  }

};
