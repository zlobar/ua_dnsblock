#!/usr/local/bin/php
<?php

$domains_unlock=array();
$files=glob(dirname(__FILE__)."/../whitelist/*");
foreach ($files as $fname) {
	unset($file);
	$file=file($fname);
	foreach ($file as $line) {
		$line=trim($line);
		if (preg_match("/^https?:\/\/(.*)/i",$line,$matches)) {
			$line=$matches[1];
		}
		if (preg_match("/^([^\/]*)\//",$line,$matches)) {
			$line=$matches[1];
		}
		$l=strtolower(trim($line));
		$l=idn_to_ascii($l);
		if ($l!="") {
			$domains_unlock[]=$l;
		}
	}
}

$domains=array();
$ips=array();
$files=glob(dirname(__FILE__)."/../blocklist/*");
foreach ($files as $fname) {
	unset($file);
	$file=file($fname);
	foreach ($file as $line) {
		$line=trim($line);
		if (preg_match("/^https?:\/\/(.*)/i",$line,$matches)) {
			$line=$matches[1];
		}
		if (preg_match("/^([^\/]*)\//",$line,$matches)) {
			$line=$matches[1];
		}
		list($l,)=explode("#",trim($line),2);
		$l=strtolower($l);
		$l=idn_to_ascii($l);
		if (preg_match("/^(2([0-4][0-9]|5[0-5])|1[0-9][0-9]|[1-9][0-9]|[0-9])\.(2([0-4][0-9]|5[0-5])|1[0-9][0-9]|[1-9][0-9]|[0-9])\.(2([0-4][0-9]|5[0-5])|1[0-9][0-9]|[1-9][0-9]|[0-9])\.(2([0-4][0-9]|5[0-5])|1[0-9][0-9]|[1-9][0-9]|[0-9])$/",$l)) {
			$ips[]=$l;
		}
		elseif ($l!="" && !in_array($l,$domains_unlock)) {
			$domains[]=$l;
		}
	}
}

$domains=array_unique($domains);
sort($domains);

$ips=array_unique($ips);
sort($ips);

$bind=array();
$unbound=array("server:");
foreach ($domains as $dom) {
	$bind[]="zone \"${dom}\"\t{ type master; file \"/usr/local/etc/namedb/primary/empty.db\"; };";
	$unbound[]="local-data: \"${dom} A 127.0.0.1\"";
}

file_put_contents(dirname(__FILE__)."/../named.conf.blocked", implode("\n",$bind)."\n");
file_put_contents(dirname(__FILE__)."/../unbound.conf.blocked", implode("\n",$unbound)."\n");
file_put_contents(dirname(__FILE__)."/../domains.txt", implode("\n",$domains)."\n");
file_put_contents(dirname(__FILE__)."/../ips.txt", implode("\n",$ips)."\n");

?>
