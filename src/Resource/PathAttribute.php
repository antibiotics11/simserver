<?php

namespace simserver\Resource;

class PathAttribute {

  public const BASENAME  = "basename";
  public const EXTENSION = "extension";
  public const DIRNAME   = "dirname";
  public const FILENAME  = "filename";
  public const RELATIVE  = "relative";
  public const ABSOLUTE  = "absolute";

  public static function pathinfoFlags(String $attribute = ""): int {
    switch ($attribute) {
      case self::BASENAME  : return PATHINFO_BASENAME;
      case self::EXTENSION : return PATHINFO_EXTENSION;
      case self::DIRNAME   : return PATHINFO_DIRNAME;
      case self::FILENAME  : return PATHINFO_FILENAME;
      default              : return PATHINFO_ALL;
    };
  }

};
