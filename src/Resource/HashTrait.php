<?php

namespace simserver\Resource;
use Throwable, InvalidArgumentException;

trait HashTrait {

  public function calculateHash(String $content, String $algorithm): String {

    $contentHash = "";
    try {
      $contentHash = mb_convert_encoding(hash($algorithm, $content, false), "UTF-8");
    } catch (Throwable $e) {
      throw new InvalidArgumentException($e->getMessage());
    }
    
    return $contentHash;

  }

  public function calculateAllHashes(String $content): Array {

    $hashes = [];
    foreach (hash_algos() as $algorithm) {
      $hashes[$algorithm] = $this->calculateHash($content, $algorithm);
      }
      
    return $hashes;

  }
  
  public function calculateCommonHashes(String $content): Array {

    $hashes = [];
    $hashes["crc32"]  = $this->calculateCRC32Hash($content);
    $hashes["md5"]    = $this->calculateMD5Hash($content);
    $hashes["sha1"]   = $this->calculateSHA1Hash($content);
    $hashes["sha256"] = $this->calculateSHA256Hash($content);

    return $hashes;

  }

  public function calculateCRC32Hash(String $content): String {
    return $this->calculateHash($content, "crc32");
  }

  public function calculateMD5Hash(String $content): String {
    return $this->calculateHash($content, "md5");
  }

  public function calculateSHA1Hash(String $content): String {
    return $this->calculateHash($content, "sha1");
  }

  public function calculateSHA256Hash(String $content): String {
    return $this->calculateHash($content, "sha256");
  }

};
