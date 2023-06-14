<?php

namespace simserver\Resource;

class Resource {

  protected String $mimeType;
  protected String $contents;
  protected int    $size;

  public function __construct(String $mimeType = MimeType::TYPE_TXT, String $contents = "") {
    $this->setMimeType($mimeType);
    $this->setContents($contents);
  }

  public function setMimeType(String $mimeType): void {
    $this->mimeType = $mimeType;
  }

  public function getMimeType(): String {
    return $this->mimeType;
  }

  public function getSize(): int {
    return $this->size;
  }

  public function setContents(String $contents): void {
    $this->contents = $contents;
    $this->size = strlen($contents);
  }

  public function getContents(): String {
    return $this->contents;
  }

};
