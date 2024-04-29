#!/usr/local/bin/php
<?php

$domains_unlock=array();
$files=glob(dirname(__FILE__)."/../whitelist/*");
foreach ($files as $fname) {
	unset($file);
	$file=file($fname);
	foreach ($file as $line) {
		$l=strtolower(trim($line));
		$l=idn_to_ascii($l);
		if ($l!="") {
			$domains_unlock[]=$l;
		}
	}
}

$domains=array();
$files=glob(dirname(__FILE__)."/../blocklist/*");
foreach ($files as $fname) {
	unset($file);
	$file=file($fname);
	foreach ($file as $line) {
		list($l,)=explode("#",trim($line),2);
		$l=strtolower($l);
		$l=idn_to_ascii($l);
		if ($l!="" && !in_array($l,$domains_unlock)) {
			$domains[]=$l;
		}
	}
}

$domains=array_unique($domains);
sort($domains);

$bind=array();
$unbound=array("server:");
foreach ($domains as $dom) {
	$bind[]="zone \"${dom}\"\t{ type master; file \"/usr/local/etc/namedb/primary/empty.db\"; };";
	$unbound[]="local-data: \"${dom} A 127.0.0.1\"";
}

file_put_contents(dirname(__FILE__)."/../named.conf.blocked", implode("\n",$bind)."\n");
file_put_contents(dirname(__FILE__)."/../unbound.conf.blocked", implode("\n",$unbound)."\n");
file_put_contents(dirname(__FILE__)."/../domains.txt", implode("\n",$domains)."\n");

?>