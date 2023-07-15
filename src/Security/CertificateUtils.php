<?php

namespace simserver\Security;

class CertificateUtils {

  // Verifies validity of a certificate file.
  public static function verifyCertificateFile($certificateFilePath): bool {
    $certificate = @file_get_contents($certificateFilePath);
    return ($certificate === false) ? false : self::verifyCertificate($certificate);
  }

  // Verifies validity of a certificate.
  public static function verifyCertificate(String $certificate): bool {
    return openssl_x509_parse($certificate) !== false;
  }

  // Verifies validity of a private key file.
  public static function verifyPrivateKeyFile(String $privateKeyFilePath): bool {
    $key = @file_get_contents($privateKeyFilePath);
    return ($key === false) ? false : self::verifyPrivateKey($key);
  }

  // Verifies validity of a private key.
  public static function verifyPrivateKey(String $privateKey): bool {
    return openssl_pkey_get_private($privateKey) !== false;
  }

  // Verifies consistency of a certificate and private key pair
  public static function verifyPrivateKeyWithCertificate(String $certificate, String $privateKey): bool {
    if (!self::verifyCertificate($certificate)) {
      return false;
    }
    if (!self::verifyPrivateKey($privateKey)) {
      return false;
    }
    return openssl_x509_check_private_key($certificate, $privateKey);
  }

};
