<?php

	$host = 'localhost' ;
	$db = 'bakerywebsite'; ;
	$name = 'root' ;
	$pass = '' ;
	$charset = 'utf8' ;
	
	try {
		
		$connect = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $name, $pass);
		
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION) ;
		// echo "Connection successfully <br/>" ;
		
	} catch(PDOException $e) {
		echo "Connection failed: " .$e->getMessage() ;
		
	}
	
	session_start() ;


?>