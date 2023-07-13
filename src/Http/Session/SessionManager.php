<?php

namespace simserver\Http\Session;

class SessionManager {

  private static ?self $manager = null;

  public static function getInstance(): self {

    if (self::$manager === null) {
      self::$manager = new self();
    }
    return self::$manager;

  }


  private const SESSION_DURATION      = 60 * 60 * 2;
  private const SESSION_MAX_DURATION  = 60 * 60 * 12;

  private Array $sessions;

  private function __construct() {
    $this->deleteAllSessions();
  }

  private function createSessionId(String $identifier): String {
    return hash("sha256", sprintf("%s:%f", $identifier, microtime(true)));
  }

  public function createSession(String $identifier, Array $data = []): String {

    $id = $this->createSessionId($identifier);
    $startTime = time();
    $expiryTime = $startTime + self::SESSION_DURATION;

    $this->sessions[$id] = new Session($id, $startTime, $expiryTime, $data);

    return $id;

  }

  public function getSession(String $id, bool $refresh = false): ?Session {

    if (!array_key_exists($id, $this->sessions)) {
      return null;
    }

    $session = $this->sessions[$id];

    if ($session->expiryTime < time()) {
      if ($refresh) {
        $this->refreshSessionExpiry($id, self::SESSION_DURATION);
      } else {
        $this->deleteSession($id);
        return null;
      }
    }

    if ($session->expiryTime - $session->startTime > self::SESSION_MAX_DURATION) {
      $this->deleteSession($id);
      return null;
    }

    return $session;

  }

  public function updateSession(String $id, Array $data = []): bool {

    $session = $this->getSession($id);
    if ($session === null) {
      return false;
    } else {
      $session->data = $data;
      return true;
    }

  }

  public function refreshSessionExpiry(String $id, int $time = self::SESSION_DURATION): bool {

    $session = $this->getSession($id);
    if ($session === null) {
      return false;
    } else {
      $session->expiryTime += $time;
      return true;
    }

  }

  public function deleteSession(String $id): bool {

    if (array_key_exists($id, $this->sessions)) {
      unset($this->sessions[$id]);
      return true;
    }
    return false;

  }

  public function deleteAllSessions(): void {
    $this->sessions = [];
  }

};
