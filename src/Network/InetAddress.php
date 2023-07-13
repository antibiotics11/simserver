<?php

namespace simserver\Network;

class InetAddress {

	private String  $hostname;
	private String  $address;
	private int     $addressFamily;

	private function __construct() {
		$this->hostname      = "";
		$this->address       = "";
		$this->addressFamily = AF_INET;
	}

	/**
	 * Get domain name.
	 *
	 * @return String domain name
	 */
	public function getHostname(): String {
		return $this->hostname;
	}

	/**
	 * Get IP address string.
	 *
	 * @return String IP address
	 */
	public function getAddress(): String {
		return $this->address;
	}

	/**
	 * Get IP address family for socket programming.
	 *
	 * @return int IP address family constant (AF_INET or AF_INET6)
	 */
	public function getAddressFamily(): int {
		return $this->addressFamily;
	}

	/**
	 * Get InetAddress instance by domain name.
	 *
	 * @param String $hostname domain name
	 * @return InetAddress|null InetAddress instance or null
	 */
	public static function getByHostname(String $hostname): ?self {
		return self::getAllByHostname($hostname)[0] ?? null;
	}

	/**
	 * Get all InetAddress instanceses by domain name.
	 *
	 * @param String $hostname domain name
	 * @return Array array of InetAddress instances
	 */
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

	/**
	 * Get InetAddress instance by IP address.
	 *
	 * @param String $address IP Address
	 * @return InetAddress|null InetAddress instance or null
	 */
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

	/**
	 * Get InetAddress instance by input (hostname or IP address).
	 *
	 * @param String $input hostname or IP address
	 * @return InetAddress|null InetAddress instance or null
	 */
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

	/**
	 * Check if given string is a valid IPv6 address.
	 *
	 * @param String $address
	 * @return bool True if string is a valid IPv4 address, false otherwise.
	 */
	public static function isIPv4(String $address): bool {
		return filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
	}

	/**
	 * Check if given string is a valid IPv6 address.
	 *
	 * @param String $address
	 * @return bool True if string is a valid IPv6 address, false otherwise.
	 */
	public static function isIPv6(String $address): bool {
		return filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
	}

};
