<?php

namespace simserver\Resource;

class Url {

	private String $url        = "";
	private String $scheme     = "";
	
	private String $username   = "";
	private String $password   = "";

	private String $host       = "";
	private int    $port       = 0;

	private Array  $path       = [];

	public function parseUrl(String $url): void {

		$this->setUrl($url);

		if (strpos($url, "://")) {
			list($scheme, $url) = explode("://", $url);
			$this->setScheme($scheme);
		}

		$compomenets = explode("/", $url);
		$this->parseHost($compomenets[0] ?? "");
		for ($i = 1; $i < count($compomenets); $i++) {
			$this->parsePath($compomenets[$i]);
		}

	}

	private function parseHost(String $host): void {

		$hostLength = strlen($host);

		$i   = 0;
		$chr = 0;
		$tmp = "";
		$beforeColon = "";
		$colonFound  = false;

		while ($i < $hostLength) {

			$chr = ord($host[$i]);

			if ($chr == 58) {
				$beforeColon = $tmp;
				$colonFound = true;
				$tmp = "";
			} else if ($chr == 64) {
				if ($colonFound) {
					$this->setUsername($beforeColon);
					$this->setPassword($tmp);
					$beforeColon = "";
					$colonFound = false;
				} else {
					$this->setUsername($tmp);
				}
				$tmp = "";
			} else if ($chr == 47 || $i == $hostLength - 1) {

				$tmp = sprintf("%s%c", $tmp, $chr);
				if ($colonFound) {
					$this->setHost($beforeColon);
					$this->setPort((int)$tmp);
				} else {
					$this->setHost($tmp);
				}
				$tmp = "";
			} else {
				$tmp = sprintf("%s%c", $tmp, $chr);
			}

			$i++;

		}

	}

	private function parsePath(String $path): void {

		$pathArray = [];

		$chr = 0;
		$tmp = "";
		$semicolonFound = false;
		$questionFound  = false;
		$equalFound     = false;
		$ampersandFound = false;
		$c = 0;
		for ($p = 0; $p < strlen($path); $p++) {

			$chr = ord($path[$p]);

			if ($semicolonFound || $questionFound) {

				$targetArray = $semicolonFound ? "params" : "quries";
				if (!$ampersandFound && ($chr == 38 || $p == strlen($path) - 1)) {
					if ($p == strlen($path) - 1) {
						$tmp = sprintf("%s%c", $tmp, $chr);
					}
					$ampersandFound = true;
					$equalFound = false;
					$pathArray[$targetArray][$c]["value"] = $tmp;
					$tmp = "";
					$c++;
				} else if (!$equalFound && $chr == 61) {
					$ampersandFound = false;
					$equalFound = true;
					$pathArray[$targetArray][$c]["name"] = $tmp;
					$tmp = "";
				} else {
					$tmp = sprintf("%s%c", $tmp, $chr);
				}

			} else {

				if ($chr == 59) {
					$semicolonFound = true;
					$pathArray["params"] = [];
					$pathArray["basename"] = $tmp;
					$tmp = "";
				} else if ($chr == 63) {
					$questionFound = true;
					$pathArray["quries"] = [];
					$pathArray["basename"] = $tmp;
					$tmp = "";
				} else {
					$tmp = sprintf("%s%c", $tmp, $chr);
				}
				if ($p == strlen($path) - 1) {
					$pathArray["basename"] = $tmp;
				}

			}

		}

		if (count($pathArray) != 0) {
			$this->path[] = $pathArray;
		}

	}

	public function setUrl(String $url): void {
		$this->url = trim($url);
	}

	public function getUrl(): String {
		return $this->url;
	}

	public function setScheme(String $scheme): void {
		$this->scheme = strtolower(trim($scheme));
	}

	public function getScheme(): String {
		return $this->scheme;
	}

	public function setUsername(String $username): void {
		$this->username = $username;
	}

	public function getUsername(): String {
		return $this->username;
	}

	public function setPassword(String $password): void {
		$this->password = $password;
	}

	public function getPassword(): String {
		return $this->password;
	}

	public function setHost(String $host): void {
		$this->host = strtolower($host);
	}

	public function getHost(): String {
		return $this->host;
	}

	public function setPort(int $port): void {
		$this->port = $port;
	}

	public function getPort(): int {
		return $this->port;
	}

	public function setPath(Array $path): void {
		$this->path = $path;
	}

	public function getPath(): Array {
		return $this->path;
	}

};
