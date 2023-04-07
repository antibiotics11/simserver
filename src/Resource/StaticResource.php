<?php

namespace simserver\Resource;

class StaticResource extends Resource {

  private String $absolutePath;
  private Array  $hashValues;

  private int    $lastAccessed;
  private int    $lastModified;

};
