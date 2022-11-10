<?php

const HOSTS = [

	[
		"NAME"       => "127.0.0.1",                   // Server name
		"PORT"       => 80,                            // Listening port number
		"ADMIN"      => "root@localhost",              // Admin email address
		"DOC_ROOT"   => __DIR__."/test",               // Document root directory
		"DOC_INDEX"  => "index.html, index.htm",       // Document index files (Seperated by comma ",")
		"LOG_DIR"    => __DIR__."/test",               // Log directory
	],

	[
		"NAME"       => "127.0.0.2",
		"PORT"       => 80,
		"DOC_ROOT"   => __DIR__."/test2",
		"DOC_INDEX"  => "main.html",
	],

	[
		"NAME"       => "::1",
		"PORT"       => 80,
		"DOC_ROOT"   => __DIR__."/test",
		"DOC_INDEX"  => "index.html",
		"LOG_DIR"    => __DIR__."/test"
	]

];
