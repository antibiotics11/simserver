<?php

namespace HTTP;

class InetAddress {

	private String  $address;
	private int     $version = AF_INET;

	public function __construct(String $address = "") {
		
		if (strlen($address) !== 0) {
			$this->setNewAddress($address);
		}

	}

	public function setNewAddress($address): void {

		if (InetAddress::isIpv4($address)) {
			$this->version = AF_INET;
		} else if (InetAddress::isIpv6($address)) {
			$this->version = AF_INET6;
		} else {
			throw new \Exception("Invalid address ".$address);
		}

		$this->address = $address;
	
	}

	public function getAddress(): String {

		return $this->address;
	
	}

	public function getVersion(): int {
	
		return $this->version;
	
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
