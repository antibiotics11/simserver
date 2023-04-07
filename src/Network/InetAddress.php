<?php

namespace simserver\Network;

class InetAddress {

	public const IP4_LOOPBACK_ADDRESS = "127.0.0.1";
	public const IP6_LOOPBACK_ADDRESS = "::1";

	private String  $address;
	private int     $family;

	public function __construct(String $address = "") {

		if (strlen($address) == 0) {
			$address = self::IP4_LOOPBACK_ADDRESS;
		}
		$this->setNewAddress($address);

	}

	public function setNewAddress(String $address): void {

		$address = strtolower(trim($address));
		if (self::isIpv4($address)) {
			$this->family = AF_INET;
		} else if (self::isIpv6($address)) {
			$this->family = AF_INET6;
		} else {
			throw new \InvalidArgumentException();
		}

		$this->address = $address;

	}

	public function getAddress(): String {
		return $this->address;
	}

	public function getFamily(): int {
		return $this->family;
	}

	public static function isIpv4(String $address): bool {
		return (
			filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
		) ? true : false;
	}

	public static function isIpv6(String $address): bool {
		return (
			filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)
		) ? true : false;
	}

};
