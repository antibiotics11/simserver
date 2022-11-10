<?php

namespace HTTP;

class Status {

	public int $code;

	public String $message = "";
	public String $details = "";

	public static function page(Status $status): String {
		return preg_replace("/\t/", "", "
			<!DOCTYPE html>
			<html lang = \"en-us\">
			<head>
				<meta 
					http-equiv = \"content-type\" 
					content = \"text/html\" 
					charset = \"UTF-8\"
				>
				<title>".$status->message."</title>
				<style type = \"text/css\"></style>
			</head>
			<body>
				<h1>".$status->code." ".$status->message."</h1>
				<h2>".$status->details."</h2>
			</body>
			</html>
		");	
	}

};
