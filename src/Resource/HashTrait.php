<?php

namespace simserver\Resource;

trait HashTrait {

  public function getHashes(String $content): Array {

    $hashes = [];
    foreach (hash_algos() as $algorithm) {
      $hashes[$algorithm] = hash($algorithm, $content, false);
      }
      
    return $hashes;

  }

  public function getHashCRC32(String $content): String {
    return hash("crc32", $content, false);
  }

  public function getHashMD5(String $content): String {
    return hash("md5", $content, false);
  }

  public function getHashSHA1(String $content): String {
    return hash("sha1", $content, false);
  }

  public function getHashSHA256(String $content): String {
    return hash("sha256", $content, false);
  }

};
