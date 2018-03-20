<?php
$path = $_REQUEST[ 'path' ];
$domain = $_REQUEST[ 'domain' ];
$cookies = $_COOKIE;

foreach( $cookies as $name => $value ) {
	unset($_COOKIE[$name]);
	setcookie( $name , null , -1, $path, $domain );
}

echo json_encode( array( 'success' => 'ok' ) );
die;