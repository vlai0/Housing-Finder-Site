<?php
	define('DATABASE_NAME', 'ASTEM_cs148_final');
	include 'lib/Database.php';

    $thisDatabaseReader = new Database('astem_reader', DATABASE_NAME);
	$thisDatabaseWriter = new Database('astem_writer', DATABASE_NAME); 
?>