<?php

class TwitterHelper extends AppHelper{

	public $helpers = array('Html');

	public function connect(){
		$link = Router::url(array('controller'=>'twitters','action'=>'index'));
		return '<a href="'.$link.'" id="twitter-connect">Twitter Connect</a>';
	}
}