<?php

namespace simserver\Resource;

class Resource {

  protected String  $mimeType;
  protected String  $contents;

  public function __construct(String $mimeType = "", String $contents = "") {
    $this->mimeType = $miemType;
    $this->contents = $contents;
  }

  public function setExtension(String $extension): void {
    $this->mimeType = MimeTYpe::fromName($extension);
  }

  public function setMimeType(String $type): void {
    $this->mimeType = $type;
  }

  public function getMimeType(): String {
    return $this->miemType;
  }

  public function setContents(String $contents): void {
    $this->contents = $contents;
  }

  public function getContents(): String {
    return $this->contents;
  }

};
