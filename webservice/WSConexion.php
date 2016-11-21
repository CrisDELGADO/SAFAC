<?php
function getConnection(){
	$dbhost="localhost";
	$dbuser="postgres";
	$dbpass="admin";
	$dbport="5432";
	$dbname="safac_v2.0";
	$dbh = new PDO("pgsql:host=$dbhost;port=$dbport;dbname=$dbname;user=$dbuser;password=$dbpass");
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}
?>