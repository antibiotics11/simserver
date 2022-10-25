<?php

namespace HTTP;

class StaticResource {

	const TYPE_XML                = "text/xml";
	const TYPE_HTM                = "text/html";
	const TYPE_HTML               = "text/html";
	const TYPE_TXT                = "text/plain";
	const TYPE_CSS                = "text/css";
	const TYPE_SCSS               = "text/scss";
	const TYPE_JS                 = "text/javascript";
	const TYPE_PDF                = "application/pdf";
	const TYPE_ZIP                = "application/zip";
	const TYPE_JSON               = "application/json";
	const TYPE_EOT                = "application/vnd.ms-fontobject";
	const TYPE_TTF                = "application/font-sfnt";
	const TYPE_WOFF               = "application/font-woff";
	const TYPE_WOFF2              = "application/font-woff2";
	const TYPE_JPG                = "image/jpg";
	const TYPE_JPEG               = "image/jpeg";
	const TYPE_PNG                = "image/png";
	const TYPE_SVG                = "image/svg+xml";
	const TYPE_GIF                = "image/gif";

	private String $type          = "";
	private String $uri           = "";
	private String $resource      = "";
	
	public function new_resource(String $uri): void {
		
		$this->uri = $uri;
		$this->resource = file_get_contents($this->uri);

		$pathinfo = pathinfo($this->uri);
		if (!isset($pathinfo["extension"])) {
			$pathinfo["extension"] = "";
		}
		$this->set_type($pathinfo["extension"]);

	}

	public function set_resource(String $resource): void {
		$this->resource = $resource;
	}

	public function get_resource(): String {
		return $this->resource;
	}

	public function set_type(String $extension): void {

		if (strlen($extension) !== 0) {
			$extension = strtoupper($extension);
			try {
				$this->type = constant(
					"\Http\StaticResource::TYPE_".$extension
				);
			} catch (\Exception $e) {
				$this->type = StaticResource::TYPE_TXT;
			}
		} else {
			$this->type = StaticResource::TYPE_HTML;
		}

	}

	public function get_type(): String {
		return $this->type;
	}

};
