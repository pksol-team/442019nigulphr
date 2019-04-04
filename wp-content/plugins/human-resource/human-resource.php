<?php

/*
Plugin Name: Human Resource
Description: Human Resouce Plugin
Version: 1.0
Author: PK SOL
Author URI: https://www.pksol.com
*/

$dire = scandir(dirname(__FILE__).'/pages');

foreach ($dire as $key => $files) {
	if ($files != '.' && $files != '..') {
		require(dirname(__FILE__). '/pages/'.$files);
	}
	
}







