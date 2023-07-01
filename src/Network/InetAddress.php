<?php

namespace simserver\Network;

class InetAddress {

	private String  $address;
	private int     $family;

	public function __construct(String $address = "") {
		if (strlen($address) != 0) {
			$this->setNewAddress($address);
		}
	}

	public function setNewAddress(String $address): bool {

		$address = strtolower(trim($address));
		if (self::isIpv4($address)) {
			$this->family = AF_INET;
		} else if (self::isIpv6($address)) {
			$this->family = AF_INET6;
		} else {
			return false;
		}

		$this->address = $address;
		return true;

	}

	public function getAddress(): String {
		return $this->address;
	}

	public function getFamily(): int {
		return $this->family;
	}

	public static function isIpv4(String $address): bool {
		return filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
	}

	public static function isIpv6(String $address): bool {
		return filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
	}

};
