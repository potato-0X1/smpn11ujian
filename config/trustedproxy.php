<?php
// Set TRUSTED_PROXIES to the IPs of your reverse proxies in deployment.
// A wildcard is safe only when the origin is inaccessible except through a trusted proxy.
return ['proxies' => env('TRUSTED_PROXIES')];
