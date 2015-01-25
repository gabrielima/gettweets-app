<?php

	$DB_HOST     = "";
	$DB_DATABASE = "";
	$DB_USER     = "";
	$DB_PASSWORD = "";	
	
	try{
		$pdo = new PDO("mysql:dbname=".$DB_DATABASE.";host=".$DB_HOST, $DB_USER, $DB_PASSWORD);
		return $pdo;
	}catch(PDOException $e){
		exit('Database error.');
	}

?>