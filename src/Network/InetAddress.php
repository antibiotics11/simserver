<?php

namespace simserver\Network;

class InetAddress {

	private String  $hostname;          // Domain Name
	private String  $address;           // IP Address
	private int     $addressFamily;     // IP Address Family

	private function __construct() {
		$this->hostname      = "";
		$this->address       = "";
		$this->addressFamily = AF_INET;
	}

	public function getHostname(): String {
		return $this->hostname;
	}

	public function getAddress(): String {
		return $this->address;
	}

	public function getAddressFamily(): int {
		return $this->addressFamily;
	}

	public static function getByHostname(String $hostname): ?self {
		return self::getAllByHostname($hostname)[0] ?? null;
	}

	public static function getAllByHostname(String $hostname): Array {

		$dnsRecordA = @dns_get_record($hostname, DNS_A);
		$dnsRecordAAAA = @dns_get_record($hostname, DNS_AAAA);
		if ($dnsRecordA === false) {
			$dnsRecordA = [];
		}
		if ($dnsRecordAAAA === false) {
			$dnsRecordAAAA = [];
		}
		$dnsRecord = array_merge($dnsRecordA, $dnsRecordAAAA);

		$inetAddresses = [];
		foreach ($dnsRecord as $record) {
			$ip = $record["ip"] ?? $record["ipv6"];
			$inetAddress = self::getByAddress($ip);
			$inetAddress->hostname = $hostname;

			$inetAddresses[] = $inetAddress;
		}

		return $inetAddresses;

	}

	public static function getByAddress(String $address): ?self {

		$addressFamily = -1;
		if (self::isIPv4($address)) {
			$addressFamily = AF_INET;
		} else if (self::isIPv6($address)) {
			$addressFamily = AF_INET6;
		} else {
			return null;
		}

		$inetAddress = new self();
		$inetAddress->address = $address;
		$inetAddress->addressFamily = $addressFamily;

		return $inetAddress;

	}

	public static function getByInput(String $input): ?self {

		$target = strtolower(trim($input));
		$inetAddress = null;

		if (self::isIPv4($target) || self::isIPv6($target)) {
			$inetAddress = self::getByAddress($target);
		} else {
			$inetAddress = self::getByHostname($target);
		}

		return $inetAddress;

	}

	public static function isIPv4(String $address): bool {
		return filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
	}

	public static function isIPv6(String $address): bool {
		return filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
	}

};
