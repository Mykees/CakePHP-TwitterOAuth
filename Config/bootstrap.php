<?php
$default = array(

	'twitter'=>array(
		'consumerKey'=>'YOUR CONSUMERKEY',
		'consumerSecret'=>'YOUR CONSUMER SECRET',
		'callBackURL' =>'http://YOUR URL SITE/twitters/callback'
	)
);
Configure::write('Plugin.Twitter', (Configure::read('Plugin.Twitter') ? Configure::read('Plugin.Twitter') : array()) + $default);