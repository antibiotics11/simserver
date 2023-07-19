<?php

namespace simserver\Resource;

// Trait providing enum-like behavior for classes.
trait EnumLikeTrait {

  // Constructer for enum-like instance.
  public function __construct(public String $name, public String $value = "") {
    $this->name = trim($name);
    $this->value = trim($value);
  }

  private static function getReflector(): \ReflectionClass {
    if (self::$reflector === null) {
      self::$reflector = new \ReflectionClass(self::class);
    }
    return self::$reflector;
  }

  /**
   * Retrieves all the cases (enum-like instances) defined in the class.
   *
   * @return Array array of enum-like instances
   */
  public static function cases(): Array {

    $reflector = self::getReflector();
    $cases = [];

    foreach ($reflector->getConstants() as $constantName => $constantValue) {
      $cases[] = new self($constantName, $constantValue);
    }

    return $cases;

  }

  /**
   * Retrieves enum-like instance that matches the provided case name.
   *
   * @param String $caseName case name to search for
   * @return self|null       matching enum-like instance or null
   */
  public static function fromName(String $caseName): ?self {

    $cases = array_filter(self::cases(), function($caseInstance) use ($caseName) {
      return strcasecmp($caseName, $caseInstance->name) == 0;
    }, ARRAY_FILTER_USE_BOTH);

    return array_values($cases)[0] ?? null;

  }

  /**
   * Retrieves enum-like instance that matches the provided case value.
   *
   * @param String $caseValue case value to search for
   * @return self|null        matching enum-like instance or null
   */
  public static function from(String $caseValue): ?self {

    $cases = array_filter(self::cases(), function($caseInstance) use ($caseValue) {
      return strcasecmp($caseValue, $caseInstance->value) == 0;
    }, ARRAY_FILTER_USE_BOTH);

    return array_values($cases)[0] ?? null;

  }

};
