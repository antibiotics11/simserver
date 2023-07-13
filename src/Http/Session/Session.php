<?php

namespace simserver\Http\Session;

class Session {

  public String $id;                  // session id
  public int    $startTime;           // session start time (unix timestamp)
  public int    $lastUpdatedTime;     // session updated time (unix timestamp)
  public int    $expiryTime;          // session expiry time (unix timestamp)
  public Array  $data;                // session data

  public function __construct(String $id, int $startTime, int $expiryTime, Array $data = []) {
    $this->id              = $id;
    $this->startTime       = $startTime;
    $this->lastUpdatedTime = $startTime;
    $this->expiryTime      = $expiryTime;
    $this->data            = $data;
  }

};
