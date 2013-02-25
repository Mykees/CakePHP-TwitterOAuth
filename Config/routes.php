<?php
Router::connect('/twitters/callback',array('controller'=>'twitters','action'=>'callback','plugin'=>'Twitter'));
Router::connect('/twitters',array('controller'=>'twitters','action'=>'twitterInit','plugin'=>'Twitter'));