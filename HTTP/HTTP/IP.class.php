<?php

namespace HTTP;
	
class IP {

	public static function is_ipv4(string $address = "127.0.0.1"): bool {

		$address = explode(".", $address);

		if (count($address) != 4) {
			return false;
		}

		foreach ($address as $part) {
			if (!is_numeric($part) || (int)$part > 255 || (int)$part < 0) {
				return false;
			}
		}

		return true;

	}

	public static function is_ipv6(string $address = "::1"): bool {

		$address = explode(":", $address);
	
		if (count($address) > 8 || count($address) < 2) {
			return false;
		}

		foreach ($address as $part) {
			if (empty($part)) {
				$part = "0";
				continue;
			}
			if (!ctype_xdigit($part) || (int)hexdec($part) > 65535 || (int)hexdec($part) < 0) {
				return false;
			}
		}

		return true;
			
	}

};
