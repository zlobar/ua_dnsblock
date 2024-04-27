#!/usr/local/bin/php
<?php

$domains_unlock=array();
$files=glob(dirname(__FILE__)."/whitelist/*");
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
$files=glob(dirname(__FILE__)."/blocklist/*");
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

$blocked=array();
foreach ($domains as $dom) {
	$blocked[]="zone \"${dom}\"\t{ type master; file \"/usr/local/etc/namedb/primary/empty.db\"; };";
}

file_put_contents(dirname(__FILE__)."/named.conf.blocked2", implode("\n",$blocked)."\n");
file_put_contents(dirname(__FILE__)."/domains.txt2", implode("\n",$domains)."\n");
//exec("/usr/local/sbin/rndc reload");

?>
