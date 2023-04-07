#!/usr/bin/php
<?php

$url = "http://127.0.0.1";

$request = curl_init();
curl_setopt($request, CURLOPT_URL, $url);
curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
curl_setopt($request, CURLOPT_TIMEOUT, 20);
curl_setopt($request, CURLOPT_HEADER, 1);

$response = curl_exec($request);
curl_close($request);

printf("Response Header ==> \n%s", $response);
