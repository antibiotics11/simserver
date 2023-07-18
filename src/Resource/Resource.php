<?php

namespace simserver\Resource;

class Resource {

  use HashTrait;

  protected MimeType $mimeType;
  protected String   $content;
  
  protected int      $bytes;
  protected int      $length;
  
  protected Array    $hashes;
  
  public function __construct(MimeType | String $mimeType = "txt", String $content = "") {
    $this->setMimeType($mimeType);
    $this->setContent($content);
  }
  
  /**
   * Set MIME Type of the resource.
   * 
   * @param MimeType|String $mimeType MIME Type to set
   * @throws InvalidArgumentException When an invalid MIME Type is provided
   */
  public function setMimeType(MimeType | String $mimeType): void {
  
    if (!$mimeType instanceof MimeType) {
      $mimeType = strtoupper(trim($mimeType));
      $mimeType = MimeType::fromName(sprintf("TYPE_%s", $mimeType));
      if ($mimeType === null) {
        throw new \InvalidArgumentException("Invalid MIME Type provided.");
      }
    }
    $this->mimeType = $mimeType;

  }
  
  public function getMimeType(): MimeType {
    return $this->mimeType;
  }
  
  /**
   * Set content of the resource.
   *
   * @param String $content Content to set
   */
  public function setContent(String $content): void {
    $this->content = $content;
    $this->hashes = $this->calculateCommonHashes($content);
    $this->bytes = strlen($content);
    $this->length = mb_strlen($content);
  }
  
  public function getContent(): String {
    return $this->content;
  }

  /**
   * Get size of the resource in bytes.
   *
   * @return int size of the resource in bytes
   */
  public function getBytes(): int {
    return $this->bytes;
  }

  /**
   * Get length of the resource in characters.
   *
   * @return int length of the resource in characters
   */  
  public function getLength(): int {
    return $this->length;
  }

};
