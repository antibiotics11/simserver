<?php

namespace simserver\Security;
use Throwable, InvalidArgumentException;

class HashCalculator {

  /**
   * Calculates hash of given content using specified algorithm.
   *
   * @param String $content   content to calculate the hash for
   * @param String $algorithm algorithm to use for hashing
   * @return String           calculated hash
   * @throws InvalidArgumentException when an invalid algorithm is provided
   */
  public static function calculateHash(String $content, String $algorithm): String {

    $contentHash = "";
    try {
      $contentHash = mb_convert_encoding(hash($algorithm, $content, false), "UTF-8");
    } catch (Throwable $e) {
      throw new InvalidArgumentException($e->getMessage());
    }
    
    return $contentHash;

  }

  /**
   * Calculates hash for given content using all available hash algorithms.
   *
   * @param String $content content to calculate the hashes for
   * @return Array          array of hashes with algorithm names as keys and hashes as values
   */
  public static function calculateAllHashes(String $content): Array {

    $hashes = [];
    $algorithms = hash_algos();
    foreach ($algorithms as $algorithm) {
      $hashes[$algorithm] = self::calculateHash($content, $algorithm);
      }
      
    return $hashes;

  }
  
  /**
   * Calculate common hashes (crc32, md5, sha1, sha256) for given content.
   *
   * @param String $content content to calculate hashes for
   * @return Array          array of common hashes with algorithm names as keys and hashes as values
   */
  public static function calculateCommonHashes(String $content): Array {

    $hashes = [];
    $hashes["crc32"]  = self::calculateCRC32Hash($content);
    $hashes["md5"]    = self::calculateMD5Hash($content);
    $hashes["sha1"]   = self::calculateSHA1Hash($content);
    $hashes["sha256"] = self::calculateSHA256Hash($content);

    return $hashes;

  }

  public static function calculateCRC32Hash(String $content): String {
    return self::calculateHash($content, "crc32");
  }

  public static function calculateMD5Hash(String $content): String {
    return self::calculateHash($content, "md5");
  }

  public static function calculateSHA1Hash(String $content): String {
    return self::calculateHash($content, "sha1");
  }

  public static function calculateSHA256Hash(String $content): String {
    return self::calculateHash($content, "sha256");
  }

};
