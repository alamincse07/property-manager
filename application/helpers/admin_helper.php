<?php 
/**
* Name:  Real Estate CMS Pro
*
* Author: Ramazan APAYDIN
*         apaydinweb@gmail.com
*
* Website: http://ramazanapaydin.com
*
* Created:  04.15.2013
*/
function error_access($message,$getURL){
	header('Content-Type: text/html; charset=utf-8');
	echo "<script>alert('$message')</script>";
	//header("Location: " . $getURL);
}

?>